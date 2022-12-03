- [] To fix
    - [] Add post, go to main page, then to add post again
        - [] Form Model doesn't reset
      
- [] Добавление поста
    - [x] Migration
        - [x] Default post status `draft`
    - [] Перенаправление пользователя на предпросмотр
      - [] Подтверждение переводит пост в статус `pending`
        - [] Показывать пользователю постоянное сообщение
          сверху что ваш пост в статусе проверки/редактирования
    - [] Перед отправкой удалить пустые секции
    - [] Теги
        - [] Если тег новый
            - [] Сообщить пользователю, что список его новых тегов будет проверен и утвержден

- [] Редактирование поста
    - [] Remove section
        - [] If section is image, remove prev image files
    - [] Add section
    - [] Change section
        - [] If section is image, remove prev image files
    - [] Change title

- [] Как пользователь, я хочу оставлять комментарии
    - [] К контенту
    - [] К другим комментариям

- [] Change all `TooltipIcon` on `IconWithTooltip`

- [] Сделать соглашение с куками

- [] Как пользователь, я хочу оценивать комментарии и контент

- [] Как пользователь, я хочу иметь возможность иметь приватную ленту интересных ссылок и статей

- [] Подумать над переходом к прежнему состоянию страницы при навигации "Назад"
    - [] При возврате на главную, если посты есть, их не запрашивать
    - [] При переходе на пост с главной, сохранять id поста в модель

- [] Добавить в мету
    - [] <meta name="description" content="60.2k votes, 19.0k comments. 32.5m members in the AskReddit community.
      r/AskReddit is the place to ask and answer thought-provoking questions.">
    - [] <link rel="canonical"
      href="https://www.reddit.com/r/AskReddit/comments/ntofxm/what_the_scariest_true_story_you_know/">
- [] sitemap
    - [] https://developers.google.com/search/docs/advanced/crawling/overview?hl=ru
