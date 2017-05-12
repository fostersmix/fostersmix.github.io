<?php
$dbh = new PDO('mysql:host=localhost;dbname=tags', 'tags', 'SjpRGzjBR6ZSZThf');
$dbh->query('use utf8');
$words = '';

function add_tag($name)
{
    global $dbh;
    
    $stmt = $dbh->prepare('INSERT INTO tags (name) VALUES (:name);');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $dbh->lastInsertId();
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


function search_tag($needle)
{
    global $dbh;
    global $words;
    $words = explode(" ", $needle);

    foreach ($words as $key => $word) {
        if (isset($tag)) {
            $prefix = ' OR ';
        }
        $tag = ':tag' . $key;
        $subquery .= $prefix . " name = " . $tag;
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
    return $stmt->fetchAll();
}

// Входные

if (isset($_GET['add_tag'])) {
    $new_id = add_tag($_GET['name']);
    //var_dump($new_id);
    //var_dump($_GET['name']);
    if (isset($_GET['add_with_links'])) {
        $words_to_link = unserialize($_GET['add_with_links']);

        foreach ($words_to_link as $key => $word) {
            if (isset($tag)) {
                $prefix = ' OR ';
            }
            $tag = ':tag' . $key;
            $subquery .= $prefix . " name = " . $tag;
        }

        $query = "SELECT id FROM tags WHERE $subquery";

        $stmt = $dbh->prepare($query);

        foreach ($words_to_link as $key => $word) {
            $tag = ':tag' . $key;
            $stmt->bindValue($tag, $word);
        }

        $stmt->execute();

        $res = $stmt->fetchAll();
        //var_dump($res);
        foreach ($res as $key=>$val) {
            add_link($val['id'], $new_id);
            //echo "adding $val -- $new_id \r\n";
        }
    }
    header('Location: '.$_SERVER['PHP_SELF']);
    die;
}

if ($_GET['add_link']) {
    add_link($_GET['tag1'], $_GET['tag2']);
    header('Location: '.$_SERVER['PHP_SELF']);
    die;
}

if ($_GET['tag']) {
    $search = search_tag($_GET['tag']);
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
<h2>Поиск</h2>
<form method="get">
	<input type="text" name="tag" />
	<input type="submit" name="search"/>
</form>
<h3>Результаты поиска:
<?php foreach ($words as $word): ?>
	<a href="?tag=<?= trim(str_ireplace($word, '', $_GET['tag'])); ?>"><?= $word ?></a>&nbsp;
<?php endforeach; ?>
</h3>
<?php if ($search): ?>
<table border="1">
<?php
foreach ($search as $res): ?>
<tr><td><?= $res[0] ?></td><td><a href="?tag=<?= $_GET['tag'] . "+" . $res[1] ?>"><?= $res[1] ?></a></td></tr>
<?php endforeach; ?>
</table>
<?php elseif (isset($words[0])): ?>
<form method="get">
	<input type="hidden" name="name" value="<?= $words[0] ?>" />
	<input type="submit" name="add_tag" value="Добавить"/>
</form>
<?php endif; ?>
<hr/>

<h2>Добавление тегов</h2>
<form method="get">
	<input type="text" name="name" />
	<input type="hidden" name="add_with_links" value="<?= htmlentities(serialize($words)); ?>"/>
	<input type="submit" name="add_tag" value="Добавить"/>
</form>
<h2>Добавление связей</h2>
<form method="get">
	<select name="tag1">
<?php foreach ($tags as $tag): ?>
<option value="<?= $tag['id'] ?>"><?= $tag['name'] ?></option>
<?php endforeach; ?>
	</select>
	<select name="tag2">
	<?php foreach ($tags as $tag): ?>
	<option value="<?= $tag['id'] ?>"><?= $tag['name'] ?></option>
	<?php endforeach; ?>
	</select>
	<input type="submit" name="add_link"/>
</form>
<table border="1">
<?php
foreach ($links as $link): ?>
<tr><td><?= $link[0] ?></td><td><?= $link[1] ?></td></tr>
<?php endforeach; ?>

</table>
</body>
</html>
