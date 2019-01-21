<?php
$dbh = new PDO('mysql:host=localhost;dbname=tags', 'tags', 'SjpRGzjBR6ZSZThf');
//$dbh->query('use utf8');
$words = '';
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
<!doctype html>

<html lang="ru">
<head>
  <meta charset="utf-8">

  <title>Tags</title>
  <meta name="description" content="tags">
  <meta name="author" content="voland">

  <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>

<body>
<h2>Добавление</h2>
<form method="get">
  <input type="text" name="keys[]" pattern="[\S]+$" required="required" />
  <input type="text" name="keys[]" pattern="[\S]+$" required="required" />
  <input type="submit" name="add" />
</form>


<h2>Поиск</h2>
<form method="get">
	<input type="text" name="tag" pattern="[\S]+$" required="required" />
	<input type="submit" name="search"/>
</form>
<h3>Результаты поиска:
    <?php //var_dump($link_to_delete); ?>
<?php foreach ($words as $word): ?>
	<a href="?tag=<?= $link_to_delete[$word] ?>"><?= $word ?></a>&nbsp;
<?php endforeach; ?>
</h3>
<?php if ($search): ?>
<table border="1">
<?php
foreach ($search as $res): ?>
<tr><td><?= $res[0] ?></td><td><a href="?tag=<?= $_GET['tag'] . " " . $res[1] ?>"><?= $res[1] ?></a></td></tr>
<?php endforeach; ?>
</table>
<?php elseif (isset($words[0])): ?>
<form method="get">
	<input type="hidden" name="name" value="<?= $words[0] ?>" />
	<input type="submit" name="add_tag" value="Добавить"/>
</form>
<?php endif; ?>
<hr/>
<h1>Связи</h1>
<table border="1">
<?php
foreach ($links as $link): ?>
<tr><td><?= $link[0] ?></td><td><?= $link[1] ?></td></tr>
<?php endforeach; ?>

</table>

<h1>Теги</h1>
<table border="1">
<?php foreach ($tags as $tag): ?>
<tr><td><a href="?tag=<?= $tag['name'] ?>"><?= $tag['name'] ?></a></td></tr>
<?php endforeach; ?>
</table>

</body>
</html>
