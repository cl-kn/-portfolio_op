<?php
function sanitize($before)
{
    foreach ($before as $key => $value) {
        $after[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $after;
}

// 0225追加
// トークン作成関数
// function token()
// {
//     // ログインした状態と同等にするためセッションを開始
//     session_start();

//     // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換
//     $toke_byte = openssl_random_pseudo_bytes(16);
//     $csrf_token = bin2hex($toke_byte);
//     // 生成したトークンをセッションに保存します
//     $_SESSION['csrf_token'] = $csrf_token;
//     // return $_SESSION['csrf_token'];
// }

function pulldown_year()
{
    print '<input name="year" type="number" style="width:70px" class="js-changeYear">';
}

function pulldown_month()
{
    print '<select name="month" class="js-changeMonth">';
    print '<option value="01">1</option>';
    print '<option value="02">2</option>';
    print '<option value="03">3</option>';
    print '<option value="04">4</option>';
    print '<option value="05">5</option>';
    print '<option value="06">6</option>';
    print '<option value="07">7</option>';
    print '<option value="08">8</option>';
    print '<option value="09">9</option>';
    print '<option value="10">10</option>';
    print '<option value="11">11</option>';
    print '<option value="12">12</option>';
    print '</select>';
}

function pulldown_day()
{
    print '<select name="day" class="js-changeDay">';
    print '<option value="01">1</option>';
    print '<option value="02">2</option>';
    print '<option value="03">3</option>';
    print '<option value="04">4</option>';
    print '<option value="05">5</option>';
    print '<option value="06">6</option>';
    print '<option value="07">7</option>';
    print '<option value="08">8</option>';
    print '<option value="09">9</option>';
    print '<option value="10">10</option>';
    print '<option value="11">11</option>';
    print '<option value="12">12</option>';
    print '<option value="13">13</option>';
    print '<option value="14">14</option>';
    print '<option value="15">15</option>';
    print '<option value="16">16</option>';
    print '<option value="17">17</option>';
    print '<option value="18">18</option>';
    print '<option value="19">19</option>';
    print '<option value="20">20</option>';
    print '<option value="21">21</option>';
    print '<option value="22">22</option>';
    print '<option value="23">23</option>';
    print '<option value="24">24</option>';
    print '<option value="25">25</option>';
    print '<option value="26">26</option>';
    print '<option value="27">27</option>';
    print '<option value="28">28</option>';
    print '<option value="29">29</option>';
    print '<option value="30">30</option>';
    print '<option value="31">31</option>';
    print '</select>';
}
