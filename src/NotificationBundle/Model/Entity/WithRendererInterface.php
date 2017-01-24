<?php declare(strict_types=1);

namespace NotificationBundle\Model\Entity;

/**
 * Tells the sender that the "content" should be rendered
 * by an external renderer eg. Twig by the getTemplateName() template
 * with message as attribute
 *
 * @package NotificationBundle\Model\Entity
 */
interface WithRendererInterface
{
    /**
     * @return string
     */
    public function getTemplateName() : string;
}
