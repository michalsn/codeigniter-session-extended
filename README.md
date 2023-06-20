# CodeIgniter Session Extended

This library gives users the ability to view their own active sessions and remove them from devices they no longer use.

It works only with database session handler.

### Requirements

This library requires the application to comply with CodeIgniter 4 [authentication recommendations](https://codeigniter.com/user_guide/extending/authentication.html).

### Installation

In the example below we will assume, that files from this project will be located in `app/ThirdParty/session-extended` directory.

Download this project and then enable it by editing the `app/Config/Autoload.php` file and adding the `Michalsn\CodeIgniterSessionExtended` namespace to the `$psr4` array, like in the below example:

```php
<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    // ...
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'Michalsn\CodeIgniterSessionExtended' => APPPATH . 'ThirdParty/session-extended/src',
    ];

    // ...
```

Now, follow the instructions of [configuring session database handler](https://codeigniter.com/user_guide/libraries/sessions.html#configure-databasehandler) - with one distinction. You have to use `Michalsn\SessionExtended\DatabaseHandler` class instead of the core one.

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use Michalsn\SessionExtended\DatabaseHandler;

class Session extends BaseConfig
{
    // ...
    public string $driver = DatabaseHandler::class;

    // ...
    public string $savePath = 'ci_sessions';

    // ...
}
```
The last step will be to run a command that will add extra fields to the session table. To do so, run command:

```cli
php spark se:install
```

That's it. You're ready to go.

### Example

```php
// app/Controllers/Home.php
<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Michalsn\CodeIgniterSessionExtended\SessionManager;

class Sessions extends BaseController
{
    public function index()
    {
        $sm = new SessionManager();

        $data['sessions'] = $sm->list(user_id());

        return view('sessions/index', $data);
    }

    public function delete()
    {
        if (! $this->request->is('post')) {
            throw new PageNotFoundException();
        }

        $rules = [
            'id' => ['required', 'string', 'max_length[128]'],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back();
        }

        $sm = new SessionManager();

        if ($sm->delete($this->request->getPost('id'), user_id())) {
            return redirect()->back()->with('success', 'Session was successfully deleted.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }
}
```
