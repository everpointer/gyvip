<?php
require_once 'common.php';
require_once 'UserInfo.php';
require_once 'function.inc.php';
require_once 'View.php';
require_once 'model/Member.php';

function exception_handler($e) {
  error_log('[Exception] in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n" .
            "错误详情：" . $e->getMessage() ); 
  echo apiJsonResult(false, array(), '系统异常，发生未知错误');
  exit;
}
set_exception_handler("exception_handler");

if ((!isset($_GET['action']) && !isset($_REQUEST['mobile']))
    || !isset($_SESSION['uid']))
{
  header($_SERVER["SERVER_PROTOCOL"]." 500 Bad Request"); 
  exit;
}

// verify mobile user existed
if (!isset($_GET['action']) && isset($_REQUEST['mobile']) &&
    !empty($_REQUEST['mobile']))
{
  $mobile = $_REQUEST['mobile'];
  $member = new KMTK\Member();
  $result = $member->queryUserByMobile($mobile);
  //// for testing
  // $result['TELEPHONE'] = '13184342077';
  // $result['CARDNO'] = '143703822043';
  if ($result && !empty($result)) {
    $_SESSION['bindingMember'] = fromKmtkMember($result);
    $_GET['action'] = 'verifyMobile';
  } else if (empty($result)) {
    echo $twig->render('message.html', array(
      'msg' => "没有找到对应的会员"
    ));
    exit;
  } else {
    $member->triggerError();
    exit();
  }
}
// verify condition
if (!isset($_SESSION['bindingMember'])) {
  header("Location: bindMember.php");
  exit();
}
$member = $_SESSION['bindingMember'];
// bindingMember actions
$action = $_GET['action'];
if ($action == 'verifyMobile') {
  echo $twig->render('verifyMobile.html', array(
    'mobile' => $member['mobile'],
    'leancloud_app_id' => $config['leancloud']['app_id'],
    'leancloud_app_key' => $config['leancloud']['app_key']
  ));
} else if ($action == 'bind') {
  // todo: smscode verification
  if (!isset($_POST['smsCode'])) exit("Bad request.");
  $smsCode = $_POST['smsCode'];
  $api = new LyfMember\Api();
  $verifyResultStr = $api->callExtUrl('verifySmsCode', array("mobilePhoneNumber" => $member['mobile']), $smsCode);
  $verifyResult = json_decode($verifyResultStr);
  // temply ignore smscode verification
  // $verifyResult = true;
  
  if($verifyResult && !isset($verifyResult->error)) {
    // create old member on leancloud
    $member['uid'] = $_SESSION['uid'];
    $member['platform'] = $_SESSION['platform'];
    $member['from'] = 'store';  // 来自门店的老会员
    $resultStr = $api->call('registerMember', $member);
    $result = json_decode($resultStr);
    if(!$result || isset($result->data->error)) {
      header($_SERVER["SERVER_PROTOCOL"]." 501 Bad Request"); 
      error_log('Api: registerMember 调用失败，原因：' . $result->data->message);
      exit();
    } else if (!empty($result->message)) {
      die("发生错误：$result->message");
    }
    // todo：赠送9积分，纪录云平台, 先不做重复判断
    // 用户积分充值
    $result = reward_credit_gift($member['cardNumber'], $config['credit_gift']);
    if ($result != true) {
      echo apiJsonResult(false, array(), $result['error']);
      exit;
    }
    unset($_SESSION['bindingMember']);
    echo apiJsonResult(true, array());
    exit;
  } else {
    // header code 501
    echo apiJsonResult(false, array(), '内部错误，手机号码验证失败');
    exit;
  }
} else {
  exit("No resources founded");
}

// ----------------------------------------------------------------------------
// functoins
function apiJsonResult($success, $data, $errMsg = "") {
  return json_encode(array(
    "success" => $success,
    "data" => $data,
    "errMsg" => $errMsg
  )); 
}
// 首次绑定积分赠送
function reward_credit_gift($cardNumber, $credit) {
  if ($credit > 0) {
      // checking credit gift reward records
      $prizeType = "initial_bind";
      $prizes = queryMemberPrice($cardNumber, $prizeType);
      // 是否已获得过奖品
      if ($prizes && !empty($prizes)) {
        // error_log("Fail to createMemberPrize when cardNumber:" . $cardNumber . " cause already binded");
        return true; 
      }
      
      $apiParams = array(
        'name' => $cardNumber,
        'userType' => 2, // member card
        'amount' => $credit, // 9积分
        'businessId' => 1,
        'orderId' => '',  // temply
        'merchantId' => 'ZB001',
        'opId' => 'gyvip',
        'opName'=> '果忆会员系统',
        'description' => '电子会员系统首次绑定积分赠送'
      );
      $apiParams['sign'] = genKmtkApiSign($apiParams);
      $api = new LyfMember\Api();
      $responseStr = $api->call('kmtkDepositScore', $apiParams);
      $response = json_decode($responseStr);
      if ($response) {
        // 首次绑定积分奖品赠送纪录
        $result = createMemberPrize($cardNumber, $prizeType, array("type" => "credit", "content" => $credit));
        if ($result != true) {
          error_log("Fail to createMemberPrize when cardNumber:" . $cardNumber . " initially bind.");
        }
        return true;
      } else if(!$response || isset($response->data->error)) {
        return array(
          "success" => false,
          "error" => $response->data->error
        );
      } else if (!empty($response->message)) {
        return array(
          "success" => false,
          "error" => $response->message
        );
      }
  } 
  return true;
}
// query Member price by cardNumber and prize type
function queryMemberPrice($cardNumber, $type) {
  $query = new leancloud\AVQuery('MemberPrize');
  $query->where('cardNumber', $cardNumber);
  $query->where('type', $type);
  $results = $query->find()->results;
  return $results;
}

// 创建用户奖品纪录 (leancloud)
// type: initial_bind
// prize: array("type" => "credit, content => 9)
function createMemberPrize($cardNumber, $type, $prize) {
  try {
    $memberPrize = new leancloud\AVObject("MemberPrize");
    $memberPrize->type = $type;
    $memberPrize->prize = json_encode(array(
      "type" => $prize['type'],
      "content" => $prize['content']
    ));
    $memberPrize->cardNumber = $cardNumber;
    $memberPrize->save();
    return true;
  } catch (Exception $e) {
    return array(
      "success" => false,
      "error" => "Fail to save member pirze record due to:" . $e->getMessage()
    );
  }
}