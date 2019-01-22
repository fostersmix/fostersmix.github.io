<?php
$dbh = new PDO('mysql:host=localhost;dbname=tags', 'tags', 'SjpRGzjBR6ZSZThf');
//$dbh->query('use utf8');
$words = '';
$search = false;
$link_to_delete = array();

if (isset($_GET['keys'])) {
  $keys = $_GET['keys'];
  $primary = array_shift($keys);
  $primary_id = add_or_get($primary);
  if (is_array($keys))
    foreach ($keys as $key) {
      $id = add_or_get($key);
      add_link($primary_id, $id);
    }
}

function add_or_get($name) {
  global $dbh;

  $stmt = $dbh->prepare('SELECT id FROM tags WHERE name = ?');
  $stmt->execute([$name]);
  $tag = $stmt->fetch();

  if (!isset($tag['id'])) {
    $stmt = $dbh->prepare('INSERT INTO tags (name) VALUES (:name);');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $dbh->lastInsertId();
  } else {
    return $tag['id'];
  }
}


function add_link($tag1, $tag2)
{
    global $dbh;
    if ($tag1 !== $tag2) {
        $stmt = $dbh->prepare('INSERT INTO links (tag1, tag2) VALUES (:tag1 , :tag2);');

        if ($tag1 < $tag2) {
            $stmt->bindParam(':tag1', $tag1);
            $stmt->bindParam(':tag2', $tag2);
        } else {
            $stmt->bindParam(':tag1', $tag2);
            $stmt->bindParam(':tag2', $tag1);
        }

        $stmt->execute();
    }
}



if ($_GET['tag']) {
    $words = explode(" ", $_GET['tag']);

    $subquery = '';
    $prefix = '';
    foreach ($words as $key => $word) {
        if (isset($tag)) {
            $prefix = ' OR ';
        }
        $tag = ':tag' . $key;
        $subquery .= $prefix . " name = " . $tag;
        // Ссылки

        $link_to_delete[$word] = implode(" ", array_diff($words, [$word]));
    }

    $query = "SELECT tag, tags.name FROM (
              SELECT tag2 AS tag FROM `links` WHERE
              tag1 IN (SELECT id FROM tags WHERE $subquery)
              UNION ALL
              SELECT tag1 AS tag FROM `links` WHERE tag2 IN (SELECT id FROM tags WHERE $subquery)
              ) AS uniontable LEFT JOIN tags ON tags.id = uniontable.tag
              GROUP BY uniontable.tag HAVING COUNT(uniontable.tag) > " . (count($words) - 1);

    $stmt = $dbh->prepare($query);

    foreach ($words as $key => $word) {
        $tag = ':tag' . $key;
        $stmt->bindValue($tag, $word);
    }

    $stmt->execute();
    $search = $stmt->fetchAll();
}

$stmt = $dbh->prepare('SELECT * FROM tags');
$stmt->execute();
$tags = $stmt->fetchAll();


$stmt = $dbh->prepare('SELECT t1.name, t2.name FROM links AS l1 LEFT JOIN tags AS t1 ON t1.id = l1.tag1 LEFT JOIN tags as t2 ON t2.id = l1.tag2');
$stmt->execute();
$links = $stmt->fetchAll();

?>
<html>
  <head>
    <title>Чипс</title>
    <meta charset="utf-8">
    <link rel="icon" sizes="128x128" href="favicon.png">
    <link rel="stylesheet" href="styles.css" type="text/css">
    <link rel="stylesheet" href="flexboxgrid.css" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400&amp;subset=cyrillic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:600">
  </head>
  <body>
    <div class="row center-xs center-sm center-md center-lg row-between-lg screen">
      <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 bottom-20">
        <div class="row center-xs center-sm center-md start-lg middle-lg">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 top-20">
            <a href="/" class="logo">чипс</a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg top-20">
            <a href="clusters/">Кластеры</a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg top-20">
            <div class="row center-xs center-sm center-md end-lg">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="add/">+ Добавить</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-11 col-sm-11 col-md-10 col-lg-8">
        <?php
        if (is_array($words)) {
        foreach ($words as $word): ?>
        	<a href="?tag=<?= $link_to_delete[$word] ?>" class="selected"><?= $word ?></a>
        <?php endforeach;

      }
        if ($search) {
          foreach ($search as $res) { ?>
          <a href="?tag=<?= $_GET['tag'] . " " . $res[1] ?>"  class="available"><?= $res[1] ?></a>
        <?php
          }
        } elseif (!$words) {
          foreach ($tags as $tag) { ?>
          <a href="?tag=<?= $tag['name'] ?>"  class="available"><?= $tag['name'] ?></a></td></tr>
        <?php }
        }
        ?>

      </div>
      <div class="col-xs-9 col-sm-9 col-md-10 col-lg-11 top-20 bottom-20">
        <div class="row center-xs center-sm center-md start-lg">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            © 2018 · <a href="about/">О чипсе</a> · <a href="mailto:mailbox@usechips.app">Написать на почту</a> · <a href="http://t.me/usechips">Чипсы в телеграме</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
