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

## Quick Usage

In the `assets.php` config file, define the groups of assets your application uses. These groups should be based on which assets a view might need so if a view uses jquery ui elements, you might want to create a jquery group that includes all the jquery files that a view might use.

    return array
    {
        'jquery' => array(
            // array($type, $url, $section, $weight),
            array('script', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js', 'body'),
            array('script', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js', 'body', 10),
        ),
    } 

Generally, it's good to have 2 sections for assets. I use `body` to indicate assets that need to be appended to the bottom of the file, and `head` to indicate assets that need to be placed in the `<head>` section.

In your code, you can mark an asset group for inclusion using `Assets::group()` or `Assets::group()`.This is generally done in `View` constructors if using View Classes.

    public function __construct(Assets $assets)
    {
        $this->_assets = $assets;
        // This view requires jquery
        $this->_assets->group('jquery');
    }

At this point, you can use `Assets::get()` to retrieve all assets that were added by section.

    $assets->get('body'); // Retrieves any asset in the `body` section within all required groups.

## Passing Javascript variables

You can pipeline server-side information into client-side Javascript variables using the `pass` method:

    $this->assets->pass([
            'route' => [
                'name'       => Route::name($this->request->route()),
                'controller' => strtolower($this->request->controller()),
                'action'     => $this->request->action()
            ],
            'url' => [
                'base'  => URL::base(),
                'media' => URL::base().Media::uri('/').'/'
            ],
            'environment' => Kohana::$environment
        ]);

When you use `Assets::get()` to retrieve all the `head` assets, data will be encoded like this:

    <script type="text/javascript">window.pass = { "route": { "name": "default", "controller": "Welcome", "action": "index" }, "url": { "base": "/", "media": "/media/kohana/" }, "environment": 40 };</script>

In Javascript, your variables are set and ready to use within the `window.pass` global variable.
