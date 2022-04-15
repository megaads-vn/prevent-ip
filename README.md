# prevent-ip
Prevent Ip

## Installation

1. Require the composer package

    `composer require megaads/prevent-ip`

2. Register the provider:

    `Megaads\PreventIp\PreventIpServiceProvider`
    
3. Add file prevent-ip.php in config:
    ```
    return [
        'enable' => true,
        'prevent_paths' => [
            'system/home'
        ],
        'allow_ips' => [
            '127.0.0.1'
        ],
        'email_receive_request' => 'author@gmail.com',
        'email_service_user' => 'abc@example.com',
        'email_service_password' => 'xxx',
        'email_service_url' => 'http://example.com/',
    ];```
   
