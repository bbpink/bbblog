$(document).ready(function() {
  var assist = function(pre, suf) {
    var area = $("#body-editor").get(0);
    var s = area.selectionStart;
    var e = area.selectionEnd;
    var res = "";
    res = area.value.slice(0, s)
        + pre
        + area.value.slice(s, e)
        + suf
        + area.value.slice(e);
    area.value = res;
  };

  $("#button-anchor").click(function() {
    assist("<a href=\"" + prompt("please input anchor URL.", "") + "\">", "</a>");
  });

  $("#button-strong").click(function() {
    assist("<span class=\"strong\">", "</span>");
  });

  $("#button-up1").click(function() {
    assist("<span class=\"sizeup-1\">", "</span>");
  });

  $("#button-up2").click(function() {
    assist("<span class=\"sizeup-2\">", "</span>");
  });

  $("#button-quote").click(function() {
    assist("<blockquote>", "</blockquote>");
  });

  $("#button-image").click(function() {
    assist("<img src=\"" + prompt("please input image URL.", "") + "\" />", "");
  });

  var scrolling = false;
  var nextto = 0;
  $(window).on("scroll", function() {
    if (scrolling) {
      var now = parseInt((new Date)/1000);
      if ((nextto + 1) < now) {
        scrolling = false;
        nextto = now;
      } else {
        return;
      }
    }

    var last = $("li:last").offset();
    var p = $(window).height() + $(window).scrollTop();
    if (last.top <= p) {
      scrolling = true;
      nextto = parseInt((new Date)/1000);

      var c = $("li").length;
      $.get(document.URL, {offset:c}, function(data) {
        var d = $.parseJSON(data);
        $.each(d, function(k, v) {
          var n = $("li:last").clone();
          n.attr("id", "entry" + v.id);
          n.find("span.title").text(v.title);
          n.find("span.created").text(v.created);
          $("li:last").after(n);
        });
      });
    }
  });

  $(document).on("click", ".button-edit", function() {
    var id = $(this).parent().attr("id").replace("entry", "");
    var current = location.href;
    location.href = current.replace("backnumber", "") + "top&id=" + id;
  });

  $(document).on("click", ".button-delete", function() {
    var id = $(this).parent().attr("id").replace("entry", "");
    if (confirm("are you sure to delete '" + $(this).parent().find(".title").text() + "' ?")) {
      alert("unsupported!");
    }
  });

});
