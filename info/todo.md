# MVP
- To FIX
  - [x] Create post with more than allowed number of images
        - [x] Check max files also if post was created and already has files
            - [x] Check that total post images less than 10

- [] Post CRUD finish
    - [] Create Policy for post CRUD
        - [x] User can update only own post if it has status `draft`
        - [x] User can delete own post if it has status `draft`
        - [] User can't create more than 3 posts per day

- [] Sitemap
    - [] Finish
        - [] Search page
            - [] Fix redundant fetching tags
                - [] Add tag to form and click search
    - [] Sitemap Service
        - [] Creates sitemap.xml as index
        - [] Each url has `lastmod`
        - [] Each is for month
        - [] methods:
            - [] `createSitemap($fileName)`
            - [] `updateSitemap($fileName, $newURLs, $type = 'post|index|pages|tags')`
                - [] It should update `lastmod` for month too
    - [] Create main sitemap that have links to sub sitemaps
        - [] Sitemap for posts
            - [] Each post has its images
        - [] Sitemap for tags
            - [] Create tag page

- [] User login length 5 - 15
- [] When a post has been edited, review doesn't show changes
- [] Translate text 'We have emailed your password reset link!'
- [] Refactor Post TagField to common/TagField


------------------------------- Fixes ----------------------------------
- [] If first tag result didn't give result, don't send request on next typing
    - [] If next type has more symbols
    - [] If next type wasn't reduce symbols
- [] To fix
    - [] Post validation
        - [] When there is only one error, it displays it for all sections
    - [] Add image as empty field, fix 500 error
        - [] Before submit remove all empty image fields

- [] Registration
    - [] User can have up to 10 drafts, display total and available on creating/editing post
    - [] Post can have max 20 tags
    - [] User can create up to 40 tags with status `penging`
    - [] Allow Registration

- [] If I'm the author of the post or not
    - [] It would be cool to see two tabs to see comments in their usual way
    - [] The second tab is to show comments by recent!
    - [] To do the same for comment Author

- [] Change all `TooltipIcon` on `IconWithTooltip`

- [] Сделать соглашение с куками

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
- [] Саша, таксист, пусть публикует на сайте свои работы. А ниже делает ссылку на свой профиль
  - [] На сайте могут публиковать в постах ссылки только те, кто делает что-то руками
    каналы трубы, телеги, могу только в профилях заполнять