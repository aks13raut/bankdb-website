$(document).ready(function() {
  var block_arr = $('.ticker li p').map(function() { return $(this).get(0);}).toArray();
  
  var ticker_item = $(block_arr[0]);
  $(".ticker").html(ticker_item);
  var ticker_width = $(".ticker").width();
  var text_x = ticker_width;
    
  console.log(block_arr.indexOf(ticker_item.get(0)));
  console.log(block_arr.length);

  scroll_ticker = function() {    
    text_x--;
    ticker_item.css("left", text_x);
    if (text_x < -1 * ticker_item.width()) {
      ticker_item = $(block_arr[(block_arr.indexOf(ticker_item.get(0)) + 1 == block_arr.length) ? 0 : block_arr.indexOf(ticker_item.get(0)) + 1]);
      ticker_item.css("left", text_x);
      $(".ticker").html(ticker_item);
      text_x = ticker_width;
    }
  }
  setInterval(scroll_ticker, 2);
});
