<?php

namespace App\Service;

use App\Exceptions\FailException;
use App\Exceptions\UnauthorizedException;
use App\Models\GameCompany;
use App\Models\MemberGameWallet;
use App\Service\Games\Factories\GameFactory;
use Illuminate\Support\Facades\DB;

class WalletService
{
    private $user;

    public function __construct()
    {
        $user = auth()->user();
        if (!$user) {
            throw new UnauthorizedException();
        }
        $this->user = $user;
    }

    public function getBalance()
    {
        return $this->user->wallet->amount;
    }

    public function getGameBalance()
    {
        $wallets = MemberGameWallet::where('member_id', $this->user->id)->get();
        $factory = new GameFactory();
        $keys = $wallets->filter(function ($wallet) use ($factory) {
            return $factory->getInstance($wallet->company->key) ?? false;
        })->map(function ($wallet) use ($factory) {
            return $factory->getInstance($wallet->company->key);
        })->toArray();

        if (count($keys)) {
            $res = (new GameService($this->user))->getBalance($keys);
            // $res = ['sa-electron' => 10];

            foreach ($res as $key => $value) {
                $wallet = $wallets->filter(function ($wallet) use ($key) {
                    return $wallet->company->key == $key;
                })->first();

                if (!$wallet) {
                    throw new FailException('game company key setting error.');
                }

                $wallet->amount = $value;
                if (is_numeric($value)) {
                    $wallet->saveOrFail();
                }
            }
        }

        return $wallets;
    }

    public function transferOut(GameCompany $company)
    {
        if (!$this->user->wallet < 0) {
            throw new FailException('Insufficient balance.');
        }

        DB::transaction(function () use ($company) {
            # log

            # transfer
            $factory = (new GameFactory())->getInstance($company->key);
            $status = (new GameService($this->user))->deposit($factory, $this->getBalance());
            if (!$status) {
                return;
            }

            $game_wallet = MemberGameWallet::where('member_id', $this->user->id)->where('company_id', $company->id)->first();
            $game_wallet->amount = $this->getBalance();
            $game_wallet->saveOrFail();
            $this->user->wallet->amount = 0;
            $this->user->wallet->push();
        });
    }

    public function transferBack()
    {
        $wallets = MemberGameWallet::where('member_id', $this->user->id)->get();
        $factory = new GameFactory();
        $keys = $wallets->filter(function ($wallet) use ($factory) {
            return $factory->getInstance($wallet->company->key) ?? false;
        })->map(function ($row) use ($factory) {
            return $factory->getInstance($row->company->key);
        })->toArray();

        if (!count($keys)) {
            return;
        }

        $res = (new GameService($this->user))->withdraw($keys);

        DB::transaction(function () use ($wallets) {
            # log

            # transfer
            $wallets->each(function ($game_wallet, $key) {
                $this->user->wallet->amount += $game_wallet->amount;
                $game_wallet->amount = 0;
                $game_wallet->saveOrFail();
            });
        });
    }
}
