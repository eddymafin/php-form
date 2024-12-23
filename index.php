<?php

$test = 4555;

$array = [1, 2, 3];
$array_2 = [
  [1, 2, 3],
  [4, 5]
];

echo $test;


// これで配列見やすくなる
echo '<pre>';
var_dump($array);
echo '</pre>';

echo $array_2[1][1];


//連想配列
$array_member = [
  'name' => '山田',
  'height' => 180,
  'hobby' => '登山'
];


echo '<pre>';
var_dump($array_member);
echo '</pre>';

$array_mountain = [
  '富士山' => [
    'height' => 3070,
    'prefecture' => '静岡県',
  ],
  '槍ヶ岳' => [
    'height' => 3010,
    'prefecture' => '長野県',
  ]
];


foreach ($array_mountain as $mountain => $value) {

  echo '名前は' . $mountain . 'です';
  foreach ($value as $key => $value) {
    echo '<br>';
    if ($key === 'height') {
      echo '高さは' . $value . 'です';
    }
    if ($key === 'prefecture') {
      echo '所在地' . $value . 'です';
    }
  }
}

echo ('<br>');

//switch分、バグの温床？になるので、できればfor文を使用する。
// breakも入れないといけない。caseは動的型推論になるので、型を指定しないといけない。

$data = 1;
switch ($data) {
  case $data === 1:
    echo 'あたりです！';
    break;
  case 2:
    echo 'あまりよくないですね';
    break;
  default:
    echo 'あなた次第です';
}


echo ('<br>');

// デフォルト関数
// 文字列の長さ
$text = '文字の長さ';
echo strlen($text);
echo ('<br>');
//ただ、日本語は、マルチバイト文字になるうので、これで意図した文字の長さを図れる
echo mb_strlen($text);

echo ('<br>');

// 文字列置換
$replace = "置換をします";
echo str_replace('置換', 'ちかん', $replace);


echo ('<br>');

// 指定文字列で分割。戻り値は、配列になる。
$split_text = "置換を、します";
var_dump(explode('、', $split_text));


echo ('<br>');

//正規表現 0、1で返ってくる
$str_3 = '特定の文字列が含まれるか';
echo preg_match('/a/', $str_3);
