FROM wolnosciowiec/docker-php-app

ENV APP_ENV=prod \
    DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db \
    NOTIFY_DB_DRIVER=pdo_sqlite \
    NOTIFY_DB_HOST=db \
    NOTIFY_DB_PORT=~ \
    NOTIFY_DB_NAME=notification \
    NOTIFY_DB_USER=root \
    NOTIFY_DB_PASSWORD="" \
    NOTIFY_MAILER_TRANSPORT=smtp \
    NOTIFY_MAILER_HOST=mail \
    NOTIFY_MAILER_PORT=25 \
    NOTIFY_MAILER_USER=root@notification \
    NOTIFY_MAILER_PASSWD="" \
    NOTIFY_MAILER_ENCRYPTION=tls \
    NOTIFY_MAILER_SENDER_ADDRESS="" \
    NOTIFY_SECRET=app-secret-here \
    NOTIFY_DEFAULT_API_USER=apiuser \
    NOTIFY_DEFAULT_API_KEY=default-api-key \
    REDIS_HOST=notification_storage \
    REDIS_PORT=6379 \
    ENABLE_TWITTER=0 \
    TWITTER_GROUPS=['publish_short_news'] \
    TWITTER_CONSUMER_KEY=1234 \
    TWITTER_SECRET=56789 \
    TWITTER_ACCESS_TOKEN=YOURACCESSTOKENEHERE \
    TWITTER_TOKEN_SECRET=YOURTOKENSECRETHERE \
    ENABLE_EMAIL=1 \
    EMAIL_GROUPS=['error_report'] \
    EMAIL_DEFAULT_FROM=anarchist-report@localhost \
    EMAIL_DEFAULT_RECIPIENTS=root@localhost \
    ENABLE_SLACK=0 \
    SLACK_GROUPS=['error_report'] \
    SLACK_HOOK_URL=http://slackhookurl...something

COPY ./docker-entrypoint.sh /docker-entrypoint.sh
COPY . /var/www/html

RUN make build_docker_image

ENTRYPOINT ["/docker-entrypoint.sh"]
