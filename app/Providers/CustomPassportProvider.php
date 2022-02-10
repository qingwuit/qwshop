<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;

/**
 * CustomPassportProvider class
 *
 * @Description 自定义令牌服务提供
 * @Author hg <bishashiwo@gmail.com>
 * @Time 2021-09-21
 */
class CustomPassportProvider extends PassportServiceProvider
{
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            // 这个类路径有问题 如果有需要则修改下
            $this->app->make(CustomPassportRepositor::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
