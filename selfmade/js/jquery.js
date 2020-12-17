$(function(){
  radio();
  shopcheck();
  showFile();
  textbox();
  deleteFile();
  shop();
  deleteShop();
  eventtextbox();
  openWin();
  // closeWin();
  profile();
});

//////////////////////////////////
// adduser.php
// addevent.php
//////////////////////////////////
// テキストボックス表示/非表示
function radio(){
  $("#r0").on("click",function(){
    $("#role0,#hidden").show();
    $("#role1").hide();
  });
  $("#r1").on("click",function(){
    $("#role1,#hidden").show();
    $("#role0").hide();
  });
}

//////////////////////////////////
// addproducts.php
// result.php
// addevent.php
//////////////////////////////////
// チェックボックス取扱い店舗　必須入力
function shopcheck(){
  $("#btn1").prop("disabled",true);
  $("#shopcheck").on("click",function(){
    if($(".chk:checked").length > 0){
      $("#btn1").prop("disabled",false);
    }else{
      $("#btn1").prop("disabled",true);
    }
  });
};

// 商品画像のサムネ表示
function showFile(){
  // 画像を選択
  $("input[id=image]").change(function(){
    var file = $(this).prop("files")[0];
    // 画像以外は処理を停止
    if(!file.type.match("image.*")){
      $(this).var("");
      $("#showfile").html("");
      return;
    }

    var reader = new FileReader();
    reader.onload = function(){
      $("#showfile").css({"height":"200px",
                          "background-image":"url("+reader.result+")",
                          "background-size":"contain",
                          "background-repeat":"no-repeat"});
    }
    reader.readAsDataURL(file);
  });
};

// 選択ファイルを削除
function deleteFile(){
  // 「ファイルをクリア」表示
  $("input[type=file]").change(function(){
    $(".hidden").css("display","block");
  });

  // ファイルをクリア
  $(".hidden").on("click",function(){
    $("input[type=file]").val("");
    $("#showfile").removeAttr("style");
    $(".hidden").css("display","none");
  });
}

// キーワードその他テキストボックス
function textbox(){
  $("input[id=other]").on("click",function(){
    // 状態の取得
    var result = $("input[id=textOther]").prop("disabled");
    // 判定
    if(result){
      $("input[id=textOther]").prop("disabled",false);
    }else{
      $("input[id=textOther]").prop("disabled",true);
      $("input[id=textOther]").val("");
    }
  });
}

//////////////////////////////////
// addevent.php
//////////////////////////////////
// 管理店舗表示・非表示
function shop(){
  $("#other").on("click",function(){
    $(".shopHidden").toggle();
  });
}


//////////////////////////////////
// result.php
//////////////////////////////////
// 取扱店舗の削除
function deleteShop(){
  $("input[class=delete]").on("click",function(){
    if(!confirm("削除しますか")){
      return false;
    }
  });
}

//////////////////////////////////
// editevent.php
//////////////////////////////////
// 催事場所その他テキストボックス
function eventtextbox(){
  $("input[id=otherP]").on("click",function(){
    // 状態の取得
    var result = $("input[id=event]").prop("disabled");
    // 判定
    if(result){
      $("input[id=event]").prop("disabled",false);
      $("#eventHidden").show();
    }else{
      $("input[id=event]").prop("disabled",true);
      $("input[id=event]").val("");
      $("#eventHidden").hide();
    }
  });
}
//////////////////////////////////
// products.php
//////////////////////////////////
function openWin(){
  $(".inner").on("click",function(){
    // $(this).find(".popup").show();

    // if($(this).hasClass("on")){
    //   $(".popup").removeClass("on");
    //   $(".popup").hide();
    // }else{
      $(this).find(".popup").addClass("on");
      $(this).find(".popup").show();
    // }

  });
  $(".close").on("click",function(){
    if($(this).parents(".popup").hasClass("on")){
      $(this).parents(".popup").removeClass("on");
      $(".popup").hide();
      return false;
    }


  });

}


//////////////////////////////////
// mypage.php
//////////////////////////////////
function profile(){
  $("#username").on("click",function(){
    $("#prof").fadeToggle("fast");
  })
}
