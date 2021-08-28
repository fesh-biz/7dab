- [] Image seeding
  - [] `images` columns to delete
    - [] `filename`
    - [] `original_width`
    - [] `original_height`
    - [] `original_size`
  - [] `images` columns to add
    - [] `date_path` string /2021/8/22
    - [] `desktop_file_path` string
    - [] `original_file_path` string
    - [] `data` json
```json
{
  "original": {
    "size": "1024",
    "width": "940",
    "height": "430"
  },
  "desktop": {
    "size": "1024",
    "width": "940",
    "height": "430"
  },
  "mobile": {
    "size": "1024",
    "width": "940",
    "height": "430"
  }
}
```


- [] PostImageService
  - [] Создание папки вида `/год/месяц/день` если ее нет
  - [] Права на папку 755
  - [] имя файла `time() + random string (10chars) + fileExtension`


- [] Как пользователь, я хочу добавлять контент
  - [] Возможность добавить:
    - [] Заголовок
    - [] Текст
    - [] Картинку
    - [] Теги
      - [] Если тег новый
        - [] Сообщить пользователю, что список его новых тегов будет проверен и утвержден
- [] Как пользователь, я хочу оставлять комментарии
  - [] К контенту
  - [] К другим комментариям
- [] Сделать соглашение с куками
- [] Добавить в мету
  - [] <meta name="description" content="60.2k votes, 19.0k comments. 32.5m members in 
      the AskReddit community. r/AskReddit is the place to ask and answer thought-provoking questions.">
  - [] <link rel="canonical"
      href="https://www.reddit.com/r/AskReddit/comments/ntofxm/what_the_scariest_true_story_you_know/">
- [] sitemap
  - [] https://developers.google.com/search/docs/advanced/crawling/overview?hl=ru

- [] Как пользователь, я хочу оценивать комментарии и контент
- [] Как пользователь, я хочу иметь возможность иметь приватную ленту интересных ссылок и статей

- [] Подумать над переходом к прежнему состоянию страницы при навигации "Назад"
  - [] При возврате на главную, если посты есть, их не запрашивать
  - [] При переходе на пост с главной, сохранять id поста в модель 