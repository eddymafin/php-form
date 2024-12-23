<?php

//CSRF対策も必要。sessionで対応をする
session_start();


//クリックジャッキング対策。.htaccess側に記述することが多い
header('X-FRAME-OPTIONS:DENY');

//GET通信は、パラメータクエリーがurlのあとに表示されるので、表示されてはまずいものは、POSTで対応する
//スーパーグローバル変数 php 9種類ある
// if (!empty($_GET)) {
//   echo '<pre>';
//   var_dump($_GET);
//   echo '</pre>';
// }

if (!empty($_POST)) {
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
}

// if (!empty($_SESSION)) {
//   echo '<pre>';
//   var_dump($_SESSION);
//   echo '</pre>';
// }

$pageFlag = 0;
//戻るの場合は、下記のように定期されていないので、最初のページに戻る
if (!empty($_POST['btnConfirm'])) {
  $pageFlag = 1;
}
if (!empty($_POST['btnSubmit'])) {
  $pageFlag = 2;
}

if (!empty($_POST['backButton'])) {
  $pageFlag = 0;
}

//xxs対策。サニタイズ(消毒)という。主に、formのinputでjsを動作させないように、文字コードを対応する
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>


</head>

<body>
  <?php if ($pageFlag === 0) : ?>
    <?php
    if (!isset($_SESSION['csrfToken'])) {
      $csrfToken = bin2hex(random_bytes(32));
      $_SESSION['csrfToken'] = $csrfToken;
    }

    $token = $_SESSION['csrfToken'];
    ?>
    <form action="index.php" method="POST">
      <h3 class="text-lg text-center mt-10">お問い合わせ</h3>

      <div class="mt-5 [&>dl]:grid [&>dl]:grid-cols-[150px_1fr] [&>dl]:items-center  [&>dl]:gap-2 [&_label]:flex [&_label]:gap-1 [&_input]:border flex flex-col gap-5 max-w-[500px] mx-auto">
        <dl>
          <dt>氏名</dt>
          <dd><input class="w-full" type="text" name="name" value="<?php if (!empty($_POST['name'])) {
                                                                      echo h($_POST['name']);
                                                                    } ?>"></dd>
        </dl>
        <dl>
          <dt>メールアドレス</dt>
          <dd><input class="w-full" type="email" name="email" value="<?php if (!empty($_POST['email'])) {
                                                                        echo h($_POST['email']);
                                                                      } ?>"></dd>
        </dl>
        <dl>
          <dt>ホームページ</dt>
          <dd><input class="w-full" type="url" name="url" value="<?php if (!empty($_POST['url'])) {
                                                                    echo h($_POST['url']);
                                                                  } ?>"></dd>
        </dl>
        <dl>
          <dt>性別</dt>
          <dd class="flex gap-2">
            <label><input type="radio" name="gender" value="0"
                <?php if (isset($_POST['gender']) && $_POST['gender'] === '0') {
                  echo 'checked';
                } ?>><span>男性</span></label>
            <label><input type="radio" name="gender" value="1"
                <?php if (isset($_POST['gender']) && $_POST['gender'] === '1') {
                  echo 'checked';
                } ?>><span>女性</span></label>
            <label><input type="radio" name="gender" value="2"
                <?php if (isset($_POST['gender']) && $_POST['gender'] === '3') {
                  echo 'checked';
                } ?>><span>その他</span></label>
          </dd>
        </dl>
        <dl>
          <dt>年齢</dt>
          <dd class="flex gap-2">
            <select name="age" id="" class="border">
              <option value="1" <?php if (isset($_POST['age']) && $_POST['age'] === '1') {
                                  echo 'selected';
                                } ?>>〜19歳</option>
              <option value="2" <?php if (isset($_POST['age']) && $_POST['age'] === '2') {
                                  echo 'selected';
                                } ?>>20歳〜29歳</option>
              <option value="3" <?php if (isset($_POST['age']) && $_POST['age'] === '3') {
                                  echo 'selected';
                                } ?>>30歳〜39歳</option>
              <option value="4" <?php if (isset($_POST['age']) && $_POST['age'] === '4') {
                                  echo 'selected';
                                } ?>>40歳〜49歳</option>
              <option value="5" <?php if (isset($_POST['age']) && $_POST['age'] === '5') {
                                  echo 'selected';
                                } ?>>50歳〜59歳</option>
              <option value="6" <?php if (isset($_POST['age']) && $_POST['age'] === '6') {
                                  echo 'selected';
                                } ?>>60歳〜</option>
            </select>
          </dd>
        </dl>
        <dl>
          <dt>お問い合わせ内容</dt>
          <dd class="flex gap-2">
            <!-- textareaは、valueでなく、閉じタグ内で呼び出す -->
            <textarea name="contact" id="" class="border w-full"><?php if (!empty($_POST['contact'])) {
                                                                    echo h($_POST['contact']);
                                                                  } ?>
            </textarea>
          </dd>
        </dl>

        <div>
          <label><input type="checkbox" name="caution">注意実行をチェックする</label>
        </div>
        <input type="submit" value="確認する" name="btnConfirm" class="bg-zinc-600 text-white py-2 px-3 rounded-sm">
        <input type="hidden" name="csrf" value="<?php echo $token; ?>">
      </div>
    </form>
  <?php endif; ?>
  <?php if ($pageFlag === 1) : ?>
    <?php if ($_POST['csrf'] === $_SESSION['csrfToken']) : ?>
      <form action="index.php" method="POST">

        <h3 class="text-lg text-center mt-10">お問い合わせ 確認</h3>
        <div class="mt-5 [&>dl]:grid [&>dl]:grid-cols-[150px_1fr] [&>dl]:items-center [&>dl]:gap-2 flex flex-col gap-5 max-w-[500px] mx-auto">
          <dl>
            <dt>氏名</dt>
            <dd><?php echo h($_POST['name']); ?></dd>
          </dl>
          <dl>
            <dt>メールアドレス</dt>
            <dd><?php echo h($_POST['email']); ?></dd>
          </dl>
          <dl>
            <dt>ホームページ</dt>
            <dd><?php echo h($_POST['url']); ?></dd>
          </dl>
          <dl>
            <dt>性別</dt>
            <dd>
              <?php
              if ($_POST['gender'] === '0') {
                echo '男性';
              }
              if ($_POST['gender'] === '1') {
                echo '女性';
              }
              if ($_POST['gender'] === '2') {
                echo 'その他';
              }
              ?>
            </dd>
          </dl>
          <dl>
            <dt>年齢</dt>
            <dd>
              <?php
              if ($_POST['age'] === '1') {
                echo '〜19歳';
              }
              if ($_POST['age'] === '2') {
                echo '20歳〜29歳';
              }
              if ($_POST['age'] === '3') {
                echo '30歳〜39歳';
              }
              if ($_POST['age'] === '4') {
                echo '40歳〜49歳';
              }
              if ($_POST['age'] === '5') {
                echo '50歳〜59歳';
              }
              if ($_POST['age'] === '6') {
                echo '60歳〜';
              }
              ?>
            </dd>
          </dl>
          <dl>
            <dt>お問い合わせ</dt>
            <dd><?php echo h($_POST['contact']); ?></dd>
          </dl>
          <div class="[&>input]:bg-zinc-600 [&>input]:text-white [&>input]:py-2 [&>input]:px-3 [&>input]:rounded-sm grid grid-cols-2 gap-2">
            <input type="submit" value="戻る" name="backButton">
            <input type="submit" value="送信する" name="btnSubmit">
          </div>
          <!-- ここでhiddenで保存することで、form間の値を維持できる -->
          <input type="hidden" name="name" value="<?php echo h($_POST['name']); ?>">
          <input type="hidden" name="email" value="<?php echo h($_POST['email']); ?>">
          <input type="hidden" name="url" value="<?php echo h($_POST['url']); ?>">
          <input type="hidden" name="gender" value="<?php echo h($_POST['gender']); ?>">
          <input type="hidden" name="age" value="<?php echo h($_POST['age']); ?>">
          <input type="hidden" name="contact" value="<?php echo h($_POST['contact']); ?>">
          <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']); ?>">
        </div>

      </form>

    <?php endif; ?>
  <?php endif; ?>
  <?php if ($pageFlag === 2) : ?>
    <?php if ($_POST['csrf'] === $_SESSION['csrfToken']) : ?>
      <p>送信が完了しました</p>

      <?php unset($_SESSION['csrfToken']); ?>
    <?php endif; ?>
  <?php endif; ?>

</body>

</html>