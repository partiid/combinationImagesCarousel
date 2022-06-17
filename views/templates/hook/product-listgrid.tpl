{**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 
<article class="product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
  
  {assign var="hasVariants" value={$product->getMainVariants()|count}}
	<div class="thumbnail-container" >
	{if $hasVariants}

	{block name='product_thumbnail'}
		{hook h='displayProductCombinationImages' product=$product}

		{block name='product_flags'}
		<ul class="product-flags">
			{foreach from=$product.flags item=flag}
			<li class="{$flag.type}">{$flag.label}</li>
			{/foreach}
		</ul>
		{/block}
	{/block}
</div>
	
	{else}

  <div class="dd-product-image">
    {block name='product_thumbnail'}
			  <a href="{$product.url}" class="thumbnail product-thumbnail">
					<img
					  class="ddproduct-img1"
					  src = "{$product.cover.bySize.home_default.url}"
					  alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
					  data-full-size-image-url = "{$product.cover.large.url}"
					>
				{hook h="displayDdProductHover" id_product=$product.id_product home='home_default' large='large_default'}
			  </a>
    {/block}
	 </div>
		
	{block name='product_flags'}
	  <ul class="product-flags">
		{foreach from=$product.flags item=flag}
		  <li class="{$flag.type}">{$flag.label}</li>
		{/foreach}
	  </ul>
	{/block}
	
 </div>
 {/if}

    <div class="product-description">
      {block name='product_name'}
        <h3 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h3 >
      {/block}

        {block name='product_price_and_shipping'}
          {if $product.show_price}
            <div class="product-price-and-shipping">
              {if $product.has_discount}
                {hook h='displayProductPriceBlock' product=$product type="old_price"}

                <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
                <span class="regular-price">{$product.regular_price}</span>
                {if $product.discount_type === 'percentage'}
                  <span class="discount-percentage">{$product.discount_percentage}</span>
                {/if}
              {/if}

              {hook h='displayProductPriceBlock' product=$product type="before_price"}

              <span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
              <span itemprop="price" class="price">{$product.price}</span>

              {hook h='displayProductPriceBlock' product=$product type='unit_price'}

              {hook h='displayProductPriceBlock' product=$product type='weight'}
            </div>
							{block name='product_reviews'}
		{hook h='displayProductListReviews' product=$product}
		{/block}
          {/if}
        {/block}
    </div>
		{block name='product_description_short'}
		 		  <div class="product-detail" itemprop="description">{$product.description_short nofilter}</div>
		{/block}
		
    <div class="highlighted-informations{if !$product.main_variants} no-variants{/if}">
		<div class="product-actions">
		    <div class="buttons-actions_align">
				<div class="add-quick-buttons">
						{block name='product_buy'}
						<form action="{Context::getContext()->link->getPageLink('cart')}" method="post">
						<input type="hidden" name="token" value="{Tools::getToken(false)}">
						<input type="hidden" name="id_product" value="{$product.id}">
						 <div class="add" {if !$product.quantity}disabled{/if}>
						
                          <button class="add-to-cart-buttons" style="outline: none; text-decoration: none;" data-button-action="add-to-cart" title="{l s='Add to cart' d='Shop.Theme.Actions'}" type="submit">{l s='' d='Shop.Theme.Actions'} 
						</button>

						</div>
					</form>
				</div>
			{/block}
			
			<a href="{$product.url}" title="{l s='Show' d='Shop.Theme.Actions'}" class="view">
			</a>
			
				<a href="#" class="quick-view" title="{l s='Quick view' d='Shop.Theme.Actions'}" data-link-action="quickview">
					<div class="quick-view-buttons">
					<i class="material-icons quick">&#xE8F4;</i> {l s='' d='Shop.Theme.Actions'}
					</div>
				</a>
			</div>
		</div>
  </div>
</article>
