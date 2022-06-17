{* <div class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
  {foreach from=$combinationImages item=image}
        <div class="carousel-item ">
        <a href="{$product_url}"><img src="https://{$image.url}"/></a>
        </div>
    {/foreach}
   
  </div>
</div>

{literal}
<script>

$('.carousel').carousel({
  interval: 1000
})
</script>

{/literal} *}

<div class="slideshow-miniature uk-text-center" uk-slideshow=" autoplay-interval: 1800;  ratio: 1:1;" data-carousel-id={$product_id}>
			<ul class="uk-slideshow-items">
      {foreach from=$combinationImages item=image}
				<li class="uk-text-center">
        
					<a href="{$product_url}"><img class="" src="https://{$image.url}" /></a>
				</li>
                {/foreach}
			</ul>
		
	</div>

    <script>
    
    $(document).ready(function(){
        
        
          

        if (window.matchMedia('(max-width: 991px)').matches) {
            $('div[data-carousel-id={$product_id}]').parent().parent().parent().css(   {literal}{'width':''} {/literal});
        } else {
             $('div[data-carousel-id={$product_id}]').parent().parent().parent().css(   {literal}{'width':'100%'} {/literal});
        }
    
    });
    $(window).resize(function() {
    if (window.matchMedia('(max-width: 991px)').matches) {
        $('div[data-carousel-id={$product_id}]').parent().parent().parent().css(   {literal}{'width':''} {/literal});
    }
});

     

        UIkit.slideshow($('.slideshow-miniature')).startAutoplay();
    </script>
   