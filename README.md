## RecipesWebsite

### Presentation
(mivindo) This is a project in collaboration with @chriSmile0. I wanted to build a new recipe website and asked for his help. First, I decided what type of content I wanted to create. 
I wanted to write recipes, with images and an interactive interface. Whether for 2, 4 or 6 people, I want the readers to be able to change the quantities or even choose the additional ingredients in real time.


## Render 

### Demo 
[show.webm](https://github.com/chriSmile0/RecipesWebsite/assets/113117256/2b690d00-4489-4e33-88e5-69a2230b6500)


### V1.5
- NoBot process
- DDOS prevention (banList, etc..)
- HomePage (NoBot)-> (choice between) MimiRecipes and ViewersRecipe


## Paths 

<details>
<summary>Paths(v1)</summary>
  
  ```bash 
  |db 
  -- *.db/*.php
  |home
  -- *
  |imgs
  -- *.jpg
  |inc
    --
  |meli
  -- index.php
  |screens
  -- 
  |styles
  |subs_imgs
  -- *.jpg/*.png
  |viewers
    |_ 
      -- *.php
    |home
      -- index.php
  -- *.php/*.js
  ```
</details>

<details open>
<summary>Paths(v2)</summary>
<pre>
.
├── db
│   ├── _database.db
│   ├── ,database.db
│   ├── database.db
│   ├── db_usage.php
│   └── upload_recipe.php
├── home
│   ├── index.php
│   ├── LOVE_JS.html
│   └── style_home.css
├── imgs
│   ├── banane_plantain.jpg
│   ├── choucroute.jpg
│   ├── manioc.jpg
│   └── patate_douce.jpg
├── inc
│   ├── header.php
│   ├── my_js.js
│   └── ,_.php
├── index.php
├── meli
│   └── index.php
├── README.md
├── screens
│   └── show.webm
├── styles
│   └── style.css
├── subs_imgs
│   └── 9de5c48354a905eb46a7d4ffe5431ea0.jpg
└── viewers
    ├── _
    │   ├── ___.php
    │   └── ,,,.php
    ├── home
    │   └── index.php
    ├── index.php
    ├── no_bot.js
    └── redirect.php
</pre>
</details>

### Database Managment 
#### SQLITE3
- In command line : 
  - `sqlite3 database.db` -> open the file
  - `.tables` -> to see the table 
  - `select/delete/update/etc..` -> basic sql commands
  
- In PHP : 
  - `ìnsert_recipe/ingredient(...)` -> create element in the database
  - IN command line ->  `php db_usage.php`

- ServorAccess (SOON) and PHP or command line
- SpecialAccessHidingFile (SOON MAYBE)
- Like a visitor with the form in the end of the website (NEXT VERSION)

### Features 
