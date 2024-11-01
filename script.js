function tmb_adn_parse_feed(widget,json,length,is_user){
	
	var posts = json.data,
		html = "",
		i = 0,
		j = (posts.length < length) ? posts.length : length;

	
	for(; i < j; i++){
		var post = posts[i];
		html += "<li>";
		
		if(is_user !== "true"){
			html += '<a href="https://alpha.app.net/'+post.user.username+'">' + post.user.username + '</a>: ';
		}
		
		html += post.html;
		html += "</li>";
	}
	
	widget.innerHTML = html;
	
}

function tmb_adn_fetch_feed(widget,url,length,is_user){
	jQuery.getJSON(url+"?callback=?",{format:"json"},function(json){
		tmb_adn_parse_feed(widget,json, length,is_user);
	});
}

function tmb_adn_feed(){
	var tmb_adn_widget_class = "ul.tmb-adn-widget-list",
		tmb_adn_widget = jQuery(tmb_adn_widget_class).get(),
		url = "",
		i=0,
		j=tmb_adn_widget.length;
 
	for(; i < j; i++) {
	
		var widget = tmb_adn_widget[i],
			selector = widget.getAttribute('data-selector'),
			is_user = widget.getAttribute('data-is-user'),
			length = +widget.getAttribute('data-length');
		

		if(is_user === "true"){
			url = "https://alpha-api.app.net/stream/0/users/"+selector+"/posts";
		}else{
			url = "https://alpha-api.app.net/stream/0/posts/tag/"+selector;
		}
		
		tmb_adn_fetch_feed(widget,url,length,is_user);
	}
}

jQuery(function(){
	tmb_adn_feed();
});