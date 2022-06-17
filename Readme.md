# Combination images carousel
this prestashop module allows you to display your product combinations cover on your product miniature in your store

# Dependencies 
- Uikit https://getuikit.com/

# how to use 
in order to use that module you need to create custom hook 
 (you can do that via https://mypresta.eu/modules/administration-tools/hooks-manager.html)

 and embed it in product miniature tpl file like so

 ![image](https://user-images.githubusercontent.com/45274640/174318968-4b25c1a1-6e9b-4648-bbf7-a9dc247ba10d.png)


YOu will also need to add restriction so the hook is used only when product has combinations 
i've done it by calling $product->getMainVariants() and count it, if the number > 0 that means you have combinations 


then install a module and voila 


