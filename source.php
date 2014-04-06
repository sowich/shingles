<? error_reporting(E_ALL); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>Сравнение текстов на схожесть - алгоритм шинглов - уникальный контен - реврайт</title>
	<meta name="keywords" content="Сравнение, текстов, схожесть, уникальный, контен, реврайт, алгоритм шингл"/>
	<meta name="description" content="Данный сервис позволяет сравнить два текста на уникальность после изменений."/>
	<meta name="robots" content="index, follow"/>
</head>
<body style="font-family: Tahoma;">

<div id="container" style="margin: 0 auto; width: 95%;">

	<h1 align="center">Сравнение текстов на схожесть</h1>

	<div style="float: left; clear: none; width: 48%;">
		Зачем изобретать велосипед?<br/>
		Поискал нужный текст или статью и скопировал себе на сайт.
		Но не все так просто. Думаю вы слышали о том, что лучше делать <strong>уникальный контен сайта</strong>.
		<p>
			Что может произойти если поисковик <a href="http://google.com" target="_top">Google</a> или <a
				href="http://yandex.ru" target="_top">Яндекс</a> определит, что ваш текст
			"позаимствован" с другого сайта?<br/>
			Ваш ресурс может не попасть в результаты поиска.
		</p>

		<p>
			Как же поисковые машины определяют схожесть текстов?<br/>
			Существует "<strong>алгоритм шинглов</strong>" (shingles-Шинглы), позволяющий простой <strong>проверкой
				двух текстов</strong> убедиться, что между ними есть связь.
		</p>

		<p>
			Как работает "<strong>алгоритм шингл</strong>"?<br/>
			Разбиение текстов на слова, а затем сравнение полученных матриц. Так что, становиться
			не важно если вы просто переставили слова или предложения (если деление идет на 1 слово).
			Разбиение текста может быть как по одному слову, так и по несколько, т.e. шингла из нескольких слов.
		</p>

		<p>
			Данный сервис позволяет сравнить два текста на уникальность после изменений.
		</p>

		<p>
			Для проверки вам необходим оригинал текста и переделанная (реврайт) копия.
		</p>

		<p>
			Идея взята с сайта "Тексторубка" (http://textorubka.ru/test.php).<br/>
			Код (PHP) был написан с нуля, но после того, как был найден более красивый код, был заменен на него
			с моими вставками.
		</p>

		<p>
			Версия: 1.0<br/>
			<a href="/shingles_php.rar">PHP код алгоритм шинглов</a>
		</p>

		<p>
			<a href="http://rikuz.com/seo/">Создание оригинальных текстов и Раскрутка сайтов</a>
		</p>
	</div>
	<div style="float: right; width: 48%;">
		Перед сравнением текст проходит минимальные чистки и изменения:<br/>
		- убираются html вставки такие как &lt;strong&gt;<br/>
		- символы преобразуются в нижний регистр<br/>
		- убираются запятые, точки, апострофы, знаки переноса строки, двойные пробелы, слешы.<br/>
		<br/>

		<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
			<strong>Оригинальный текст</strong>:<br/>
			<textarea id="text1" name="text1"
			          style="width: 100%; height: 200px;"><?= isset($_POST['text1']) ? stripslashes(htmlspecialchars($_POST['text1'])) : '' ?></textarea><br/>
			<strong>Переделанная (реврайт) копия</strong>:<br/>
			<textarea id="text2" name="text2"
			          style="width: 100%; height: 200px;"><?= isset($_POST['text2']) ? stripslashes(htmlspecialchars($_POST['text2'])) : '' ?></textarea><br/>
			<br/>
			<input type="submit" value="Проверить" style="display: block; margin: 0 auto; font-weight: bold; width: 50%;"/>
		</form>
		<p>
			<?php
			function get_shingle($text, $n = 3) {
				$shingles = array();
				$text = clean_text($text);
				$elements = explode(" ", $text);

				for ($i = 0; $i < (count($elements) - $n + 1); $i++) {
					$shingle = '';
					for ($j = 0; $j < $n; $j++) {
						$shingle .= mb_strtolower(trim($elements[$i + $j]), 'UTF-8') . " ";
					}
					if (strlen(trim($shingle)))
						$shingles[$i] = trim($shingle, ' -');
				}

				var_dump($shingles);die;
				return $shingles;
			}

			function clean_text($text) {
				$new_text = preg_replace("/[\,|\.|\'|\"|\\|\/]/", "", $text);
				$new_text = preg_replace("/[\n|\t]/", " ", $new_text);
				$new_text = preg_replace('/(\s\s+)/', ' ', trim($new_text));
				return $new_text;
			}

			function check_it($first, $second) {
				if (!$first || !$second) {
					echo "Отсутствуют оба или один из текстов!";
					return 0;
				}

				if (strlen($first) > 200000 || strlen($second) > 200000) {
					echo "Длина обоих или одного из текстов превысила допустимую!";
					return 0;
				}


				for ($i = 4; $i < 5; $i++) {
					$first_shingles = array_unique(get_shingle($first, $i));
					$second_shingles = array_unique(get_shingle($second, $i));

					echo $first_shingles;
					die;

					if (count($first_shingles) < $i - 1 || count($second_shingles) < $i - 1) {
						echo "Количество слов в тексте меньше чем длинна шинглы<br />";
						continue;
					}

					$intersect = array_intersect($first_shingles, $second_shingles);

					$merge = array_unique(array_merge($first_shingles, $second_shingles));

					$diff = (count($intersect) / count($merge)) / 0.01;

					echo "Количество слов в шингле - $i. Процент схожести - " . round($diff, 2) . "%<br />";
				}
			}

			if (isset($_POST['text1']) && isset($_POST['text2'])) {
				check_it(strip_tags($_POST['text1']), strip_tags($_POST['text2']));
			}
			?>
		</p>
	</div>
</div>

</body>
</html>