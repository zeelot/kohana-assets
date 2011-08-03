# Assets

*Define dependencies between assets and views*

- **Version:** 1.1.0
- **URL:** <http://github.com/Zeelot/kohana-assets>
- **Compatible Kohana Version(s):** 3.2.x

## Description
Create an instance of `Assets` for your views to use to request css and js files. Pass the object to any child view (layout view, page view, and any widget view) for them to request additional assets. Then, after rendering the html, use the object to insert the assets into the header (or footer) of the page.

### Why an instance instead of a static collector class?

Static collector classes quickly fail when you need to keep track of multiple sets of asset requirements. If you are using Views to generate a PDF, you will want to create a new `Assets` instance in order to keep track of the assets the pdf will need to include.

Static collector classes also fail when your application executes sub-requests. There is no clean way to isolate the assets required in each request's response if the collector class is static. Giving your view an instance of `Assets` will make sure that when you render a view, the assets included in that view's header are always only the ones that the view and its sub-views requested.