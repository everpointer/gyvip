var appId = 'wx13313ffb9bfbc309';
var timestamp = Math.floor(Date.now()/1000);
var nonceStr = 'Wm3WZYTPz0wzccnW';
var url = location.href.split('#')[0];
// expired at 23号10:25
// token: 53jgF2nIGN8rgvAExZyNeiokRoXDpLeaUwmD-Y9U_2yQG8FyqOUbZvU-zRtlxxddV44EaGbA9BU4usLK8QG96orQ41WU3gp1G9CjedUoc68 
var jsapi_ticket = 'sM4AOVdWfPE4DxkXGEs8VFtK8fY77OP9ds36eLLauwUtLXuf9kV_s6NH6t3Az75OrrX3lRDhh7E7rI5X_lRhVA'; // Temply cached, request at server on production 

var signature = sign({
  jsapi_ticket: jsapi_ticket,
  nonceStr: nonceStr,
  timestamp: timestamp,
  url: url
});

// AV setting
AV.initialize('qa5qc8ob1kdwj21k25ls4kppyh6dlj8htmcngovzs4t7a8fs', 'mg2vp8lhwpvtsyc3ulldjadtgvwx6fj2x3o5w5ljs80mvbg0');

// temp
var uid = Date.now().toString();
// var uid = "1234567890";

wx.config({
  debug: true,
  appId: appId,
  timestamp: timestamp,
  nonceStr: nonceStr,
  signature: signature,
  jsApiList: ['onMenuShareTimeline']
});

wx.ready(function(){
  // 朋友圈
  wx.onMenuShareTimeline({
    title: "花果山果忆No.23集仕港店盛大开业",
    link: url,
    imgUrl: "https://dn-kdt-img.qbox.me/upload_files/2015/09/22/Frg7qECk0_XrbF_kHYoXZNdGvbGS.png",
    success: function() {
      // loading
      showSpinnerBox();
      // 查询用户礼品卡获取状态
      getUserJsgGiftCard(uid, {
        success: function(object) {
          if (typeof object === "undefined") {
            // 记录不存在，创建一份礼品卡
            createJsgGiftCard(uid, {
              success: function(object) {
                hideSpinnerBox();
                hideShareOverlay();
                initScreen(object);
              },
              error: function() {
                hideSpinnerBox();
                alert('礼品卡获取失败');
              },
            });
          } else {
            //记录已存在，显示已有的状态
            hideSpinnerBox();
            hideShareOverlay();
            initScreen(object);
          }
        },
        error: function() {
          hideSpinnerBox();
          hideShareOverlay();
          alert("发生未知错误");
        }
      });
    },
    cancel: function() {
      // console.log("canceled");
      hideShareOverlay();
    }
  });
});

wx.error(function(res){
  
});

$(document).ready(function($) {
  // todo: add spinner 提示
  // 初始化页面状态
  getUserJsgGiftCard(uid, {
    success: initScreen,
    error: function() {
      console.log("初始化页面失败");
    }
  });
  // 绑定事件
  $('#shareTimeline').on('click', function() {
    showShareOverlay();  
  });
  $('body').on('click', '.overlay-black', function() {
    hideShareOverlay();  
  });
  // setting modal
  $("#modal-checkbox").on("change", function() {
    if ($(this).is(":checked")) {
      $("body").addClass("modal-open");
    } else {
      $("body").removeClass("modal-open");
    }
  });

  $(".modal-fade-screen, .modal-close, .modal-cancel").on("click", function() {
    $(".modal-state:checked").prop("checked", false).change();
    // reset state
    // $("#btn_redeem_prize").text("确定").data('disabled', 'false');
  });

  $(".modal-inner").on("click", function(e) {
    e.stopPropagation();
  });
  // 测试验证礼品卡
  $(".modal-content").on('click', '#verifyJsgGiftCard', function() {
    verifyJsgGiftCard(uid, {
        success: function() {
          $(".c-modal .modal-content").html("<span style='color: rgb(32, 210, 32);'>兑换成功，您现在可以领取奖品了</span>");
        },
        error: function(msg) {
          alert("验证出错：" + msg);
        },
        fail: function(msg) {
          alert("验证失败，原因：" + msg);
        }
    });
  });
})

// funcitons
// --------------------------------------------
function sign(params) {
  var paramsString = "jsapi_ticket=" + params.jsapi_ticket +
    "&noncestr=" + params.nonceStr + "&timestamp=" + params.timestamp +
    "&url=" + url;
  // alert(paramsString);
  var shaObj = new jsSHA("SHA-1", "TEXT");
  shaObj.update(paramsString);
  var hash = shaObj.getHash("HEX");
  return hash
}

function createJsgGiftCard(uid, callbacks) {
  var card = AV.Object.new('JsgGiftCard');
  card.set('uid', uid);
  card.set('status', 'normal');
  // save时无法获得默认生成后的数据
  card.save(null, {
    success: function(card) {
      console.log("Create JsgGiftCard record successfully");
      callbacks.success(card);
    },
    error: function(card, error) {
      // 失败之后执行其他逻辑
      // error 是 AV.Error 的实例，包含有错误码和描述信息.
      alert('Failed to create new object, with error message: ' + error.message);
      callbacks.error();
    }
  });
}

function verifyJsgGiftCard(uid, callbacks) {
  getUserJsgGiftCard(uid, {
    success: function(object) {
      if (typeof object === "undefined") {
        callbacks.fail("礼品不存在");
      } else if (object.get('status') == "used") {
        callbacks.fail("已使用");
      } else {
        object.set('status', 'used');
        object.set('usedAt', (new Date()).Format("MM-dd hh:mm:ss"));
        object.save();
        callbacks.success();
      }
    },
    error: function(error) {
      callbacks.error("verifyJsgGiftCard Error: " + error.code + " " + error.message);
    }
  });
}

// 查询用户GiftCard (ajax)
function getUserJsgGiftCard(uid, callbacks) {
  var Card = AV.Object.extend('JsgGiftCard');
  var query = new AV.Query(Card);
  query.equalTo("uid", uid);
  query.first({
    success: callbacks.success,
    error: callbacks.error
  });
}



// 初始化屏幕gift card状态
function initScreen(object) {
  if(typeof object === 'undefined') {
    return;
  }
  if (object.get('status') === "used") {
    $(".c-modal .modal-content").html("<div style='color:red;'>您已兑换过奖品<br/>领取时间：" + object.get('usedAt') + "</div>");
    $("#modal-checkbox").prop("checked", true);
  } else if (object.get('status') === "normal") {
    $(".c-modal .modal-content").html( 
      "您已获得奖品<br/>请让收银员点击验证按钮，进行兑换<br/>" + "<a href='#' class='c-button c-button--secondary' id='verifyJsgGiftCard'>收银员验证</a>");
    $("#modal-checkbox").prop("checked", true);
  }
}



// spinner helpers
function showSpinnerBox(parent) {
  // add overlay
  var overlay = document.createElement('div');
  overlay.className = 'overlay';
  document.body.appendChild(overlay);
  
  var div = document.createElement('div');
  var spinnerHtml = "<div id='spinner_box' class='spinner-box'><div class='spinner-wrapper'></div><div class='spinner-text'>加载中...</div></div>";
  if (typeof parent == "undefined") {
    parent = document.body;
  }
  parent.appendChild(div).innerHTML = spinnerHtml;
  var spinnerWrapper = document.querySelector('#spinner_box .spinner-wrapper');
  // setting spin.js
  new Spinner({color:'white', lines: 17, width: 2, length: 4, radius: 8, scale: 0.5}).spin(spinnerWrapper);
}
function hideSpinnerBox() {
  var spinnerBox = document.getElementById('spinner_box');
  if (spinnerBox) {
    $(spinnerBox).remove();
    $(".overlay").remove();
  }
}

//overlay
function showShareOverlay() {
  var overlay = document.createElement('div');
  overlay.className = 'overlay-black';
  // append tip image to overlay
  var img = document.createElement('img');
  $(overlay).html("<div class='overlay-tips'><img src='images/share-tip.png' /></div>");
  document.body.appendChild(overlay); 
}
function hideShareOverlay() {
  $('.overlay-black').remove();
}

/* helpers
 */
Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}