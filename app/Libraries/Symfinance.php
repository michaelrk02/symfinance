<?php

namespace App\Libraries;

class Symfinance
{
    public static function rupiah($number)
    {
        $sign = $number < 0 ? '-' : '';
        return $sign.'IDR '.number_format(abs($number));
    }

    public static function deposit($balance, $amount, $fee)
    {
        $deposit = [];
        $deposit['amount'] = $amount;
        $deposit['fee'] = $fee;
        $deposit['balance_change'] = $deposit['amount'] - $deposit['fee'];
        $deposit['old_balance'] = $balance;
        $deposit['new_balance'] = $balance + $deposit['balance_change'];

        return $deposit;
    }

    public static function withdraw($balance, $amount, $fee)
    {
        $withdraw = [];
        $withdraw['amount'] = $amount;
        $withdraw['fee'] = $fee;
        $withdraw['balance_change'] = $withdraw['amount'] + $withdraw['fee'];
        $withdraw['old_balance'] = $balance;
        $withdraw['new_balance'] = $balance - $withdraw['balance_change'];

        return $withdraw;
    }

    public static function transfer($srcbalance, $dstbalance, $amount, $fee)
    {
        $transfer = [];
        $transfer['amount'] = $amount;
        $transfer['fee'] = $fee;
        $transfer['src_balance_change'] = $transfer['amount'] + $transfer['fee'];
        $transfer['src_old_balance'] = $srcbalance;
        $transfer['src_new_balance'] = $srcbalance - $transfer['src_balance_change'];
        $transfer['dst_balance_change'] = $transfer['amount'];
        $transfer['dst_old_balance'] = $dstbalance;
        $transfer['dst_new_balance'] = $dstbalance + $transfer['dst_balance_change'];

        return $transfer;
    }
}

