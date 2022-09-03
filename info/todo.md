- [] Как пользователь, я хочу добавлять контент
    - [] Возможность добавить пост:
        - [] Заголовок
            - [] Show error if empty
            - [] Test that error showed to user
            - [] Test that the database has that title
        - [] Текст
            - [] Show error if no sections for posts
            - [] Remove sections if they are empty
            - [] Test that error showed to user
            - [] Test that the database has all texts inside
        - [] Картинку
        - [] Теги
            - [] Если тег новый
                - [] Сообщить пользователю, что список его новых тегов будет проверен и утвержден


- [] Как пользователь, я хочу оставлять комментарии
    - [] К контенту
    - [] К другим комментариям

- [] Change all `TooltipIcon` on `IconWithTooltip`

- [] Сделать соглашение с куками


- [] Добавить в мету
    - [] <meta name="description" content="60.2k votes, 19.0k comments. 32.5m members in the AskReddit community.
      r/AskReddit is the place to ask and answer thought-provoking questions.">
    - [] <link rel="canonical"
      href="https://www.reddit.com/r/AskReddit/comments/ntofxm/what_the_scariest_true_story_you_know/">
- [] sitemap
    - [] https://developers.google.com/search/docs/advanced/crawling/overview?hl=ru


- [] Как пользователь, я хочу оценивать комментарии и контент


- [] Как пользователь, я хочу иметь возможность иметь приватную ленту интересных ссылок и статей


- [] Подумать над переходом к прежнему состоянию страницы при навигации "Назад"
    - [] При возврате на главную, если посты есть, их не запрашивать
    - [] При переходе на пост с главной, сохранять id поста в модель 
