/* ------------------------------
 conf
------------------------------ */



/* ------------------------------
 smartRollover
------------------------------ */

function smartRollover() {
	if(document.getElementsByTagName) {
var tgs = new Array("img","input");
for(var i2=0; i2 < tgs.length; i2++) {
var tg = document.getElementsByTagName(tgs[i2]);
for(var i=0; i < tg.length; i++) {
if(tg[i].getAttribute("src")){
	if(tg[i].getAttribute("src").match("_off."))			{
		tg[i].onmouseover = function() {
			this.setAttribute("src", this.getAttribute("src").replace("_off.", "_on."));
		}
		tg[i].onmouseout = function() {
			this.setAttribute("src", this.getAttribute("src").replace("_on.", "_off."));
		}
	}
}
}
}
		
	}
}

if(window.addEventListener) {
	window.addEventListener("load", smartRollover, false);
}
else if(window.attachEvent) {
	window.attachEvent("onload", smartRollover);
}


/* ------------------------------
 CurrentOverSet
------------------------------ */

function CurrentOverSet() {

	var mnum=arguments.length;
	arg = new Array(mnum);

	for (i=0;i<mnum;i++){
		arg[i]=arguments[i]
	}


function CurrentOver() {
	for (i=0;i<mnum;i++){
		var	ovimg=document.getElementById(arg[i]);

		if(ovimg!=undefined){
			ovimg.src=ovimg.src.replace("_off.", "_on.");
			ovimg.onmouseout = function(){
				return false;
			}
		}
	}
}

if(window.addEventListener) {
	window.addEventListener("load", CurrentOver, false);
}
else if(window.attachEvent) {
	window.attachEvent("onload", CurrentOver);
}

}



/* ------------------------------
 smoothScroll
------------------------------ */

/* Smooth scrolling
   Changes links that link to other parts of this page to scroll
   smoothly to those links rather than jump to them directly, which
   can be a little disorienting.
   
   sil, http://www.kryogenix.org/
   
   v1.0 2003-11-11
   v1.1 2005-06-16 wrap it up in an object
*/

var ss = {
  fixAllLinks: function() {
    // Get a list of all links in the page
    var allLinks = document.getElementsByTagName('a');
    // Walk through the list
    for (var i=0;i<allLinks.length;i++) {
      var lnk = allLinks[i];
      if ((lnk.href && lnk.href.indexOf('#') != -1) && 
          ( (lnk.pathname == location.pathname) ||
	    ('/'+lnk.pathname == location.pathname) ) && 
          (lnk.search == location.search)) {
        // If the link is internal to the page (begins in #)
        // then attach the smoothScroll function as an onclick
        // event handler
        ss.addEvent(lnk,'click',ss.smoothScroll);
      }
    }
  },

  smoothScroll: function(e) {
    // This is an event handler; get the clicked on element,
    // in a cross-browser fashion
    if (window.event) {
      target = window.event.srcElement;
    } else if (e) {
      target = e.target;
    } else return;

    // Make sure that the target is an element, not a text node
    // within an element
    if (target.nodeName.toLowerCase() != 'a') {
      target = target.parentNode;
    }
  
    // Paranoia; check this is an A tag
    if (target.nodeName.toLowerCase() != 'a') return;
  
    // Find the <a name> tag corresponding to this href
    // First strip off the hash (first character)
    anchor = target.hash.substr(1);
    // Now loop all A tags until we find one with that name
    var allLinks = document.getElementsByTagName('a');
    var destinationLink = null;
    for (var i=0;i<allLinks.length;i++) {
      var lnk = allLinks[i];
      if (lnk.name && (lnk.name == anchor)) {
        destinationLink = lnk;
        break;
      }
    }
  
    // If we didn't find a destination, give up and let the browser do
    // its thing
    if (!destinationLink) return true;
  
    // Find the destination's position
    var destx = destinationLink.offsetLeft; 
    var desty = destinationLink.offsetTop;
    var thisNode = destinationLink;
    while (thisNode.offsetParent && 
          (thisNode.offsetParent != document.body)) {
      thisNode = thisNode.offsetParent;
      destx += thisNode.offsetLeft;
      desty += thisNode.offsetTop;
    }
  
    // Stop any current scrolling
    clearInterval(ss.INTERVAL);
  
    cypos = ss.getCurrentYPos();
  
    ss_stepsize = parseInt((desty-cypos)/ss.STEPS);
    ss.INTERVAL =
setInterval('ss.scrollWindow('+ss_stepsize+','+desty+',"'+anchor+'")',10);
  
    // And stop the actual click happening
    if (window.event) {
      window.event.cancelBubble = true;
      window.event.returnValue = false;
    }
    if (e && e.preventDefault && e.stopPropagation) {
      e.preventDefault();
      e.stopPropagation();
    }
  },

  scrollWindow: function(scramount,dest,anchor) {
    wascypos = ss.getCurrentYPos();
    isAbove = (wascypos < dest);
    window.scrollTo(0,wascypos + scramount);
    iscypos = ss.getCurrentYPos();
    isAboveNow = (iscypos < dest);
    if ((isAbove != isAboveNow) || (wascypos == iscypos)) {
      // if we've just scrolled past the destination, or
      // we haven't moved from the last scroll (i.e., we're at the
      // bottom of the page) then scroll exactly to the link
      window.scrollTo(0,dest);
      // cancel the repeating timer
      clearInterval(ss.INTERVAL);
      // and jump to the link directly so the URL's right
      location.hash = anchor;
    }
  },

  getCurrentYPos: function() {
    if (document.body && document.body.scrollTop)
      return document.body.scrollTop;
    if (document.documentElement && document.documentElement.scrollTop)
      return document.documentElement.scrollTop;
    if (window.pageYOffset)
      return window.pageYOffset;
    return 0;
  },

  addEvent: function(elm, evType, fn, useCapture) {
    // addEvent and removeEvent
    // cross-browser event handling for IE5+,  NS6 and Mozilla
    // By Scott Andrew
    if (elm.addEventListener){
      elm.addEventListener(evType, fn, useCapture);
      return true;
    } else if (elm.attachEvent){
      var r = elm.attachEvent("on"+evType, fn);
      return r;
    } else {
      alert("Handler could not be removed");
    }
  } 
}

ss.STEPS = 25;

ss.addEvent(window,"load",ss.fixAllLinks);


/** more **/
$(function(){
     $(".more").click(function(){
		 $(this).parents(".mf,.cbox").addClass("op");
		 $(this).hide();
    });
});


/** 全体リンク **/
$(function(){
    $(".all").click(function(){
        window.location=$(this).find("a").attr("href");
        return false;
    });
});

$(function() {
    var topBtn = $('#pagetop');    
    var hmenu = $('#hmenu');    
    topBtn.hide();
    hmenu.hide();
    //スクロールが100に達したらボタン表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 10) {
            topBtn.fadeIn();
            hmenu.fadeIn();
        } else {
            topBtn.fadeOut();
            hmenu.fadeOut();
        }
    });
    //スクロールしてトップ
    topBtn.click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
});



/** altをテキスト化 **/
$(function(){
    $(".timg").each(function(i){
        var txt = $("img",this).attr("alt");
        $(this).attr("data-label", txt);
    });

    $(".tellink").css("cursor","pointer");
    $(".tellink").removeAttr("href");

    $(".tellink").click(function(){
        $("#telpopup-frame").show();
    });

    $("#telpopup-close a").click(function(){
        $("#telpopup-frame").hide();
    });




    /** スマホグローバルメニュー **/

    $("#gnavs a").click(function(){
        $('#navChk').removeAttr('checked').prop('checked',false).change();
    });


    $(window).on('resize', function(){

        if($(window).width() < 800){
            return;
        }
        smenuclose();
        $("#telpopup-frame").hide();
    });



    //スマホここまで




});

