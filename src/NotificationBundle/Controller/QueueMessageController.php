<?php

namespace NotificationBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use NotificationBundle\Model\Entity\MessageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("Message/Queue", pluralize=false)
 */
class QueueMessageController extends FOSRestController
{
    /**
     * @Route(requirements={"_format"="json"}, methods={"POST", "PUT"})
     * @QueryParam(name="message_type", description="Message type, mostly its just 'Message'")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $messageContent = json_decode($request->getContent(), true);

        $validation = $this->get('notificationbundle.validator.message')
            ->validate($messageContent, $request->query->get('message_type'));

        if ($validation->isSuccess() === false) {
            return new JsonResponse(
                [
                    'success' => false,
                    'reason'  => $validation->getReason(),
                    'message' => $validation->getMessage(),
                ],
                400
            );
        }

        $message = $this->get('notificationbundle.factory.message')
            ->createMessage($messageContent, $request->query->get('message_type'));

        $this->get('notificationbundle.factory.queue')
            ->getQueue()
            ->push($message);

        return new JsonResponse(
            [
                'success'     => true,
                'description' => 'Sent to queue',
                'id'          => $message->getId(),
            ],
            201
        );
    }

    /**
     * @Route(requirements={"_format"="json"}, methods={"GET"})
     *
     * @return Response
     */
    public function processAction()
    {
        $sender  = $this->get('notificationbundle.services.sender');
        $cleaner = $this->get('notificationbundle.services.cleaner.queue');
        $queue   = $this->get('notificationbundle.factory.queue')->getQueue();

        /** @var MessageInterface[] $messages */
        $messages = array_filter($queue->findAll());

        $results = $sender->send($messages);
        $cleaner->clearProcessedMessages($results);

        $view = $this->view(
            [
                'results' => $results,
            ],
            $results->isSuccess() ? 200 : 420
        );

        return $this->handleView($view);
    }
}