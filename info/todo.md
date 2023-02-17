# MVP
- [] Ability to rating
  - [] User can't vote own post/comment
  - [] User can't vote more than one per Comment/Post
  - [] JS voting step equals 2. Should be 1.
- [] Comments
    - [] User can add comment to post
    - [] User can add comment to comment

- [] Tag page
    - [] Search page
    - [] Input fields:
        - [] Text
        - [] Tags


------------------------------- Next Steps ----------------------------------
- [] Где-то применить этот слоган
  Українці зі всього світу спілкуються тут
  
- [] Замість Добавити Теревеньку - Теревенькнути
    - [] 

- [] Fix all problems with auth
- [] To fix
    - [] Add post, go to main page, then to add post again
        - [] Form Model doesn't reset
    - [] Post validation
        - [] When there is only one error, it displays it for all sections
    - [] Add image as empty field, fix 500 error
        - [] Before submit remove all empty image fields

- [] Registration
    - [] User can have up to 10 drafts, display total and available on creating/editing post
    - [] Post can have max 20 tags
    - [] User can create up to 40 tags with status `penging`
    - [] Allow Registration

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
- [] Admin tags page

------------------------------ Ideas to implementation --------------------------
- [] When user selected some existed tag(s) display recommended tags