<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class EthereumAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'signature' => 'required|string',
        ]);

        $address = $request->input('address');
        $signature = $request->input('signature');

        if (!$this->verifySignature($address, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $user = User::where('ethereum_address', $address)->first();

        if (!$user) {
            // Create new user if doesn't exist
            $user = User::create([
                'ethereum_address' => $address,
                'name' => 'ETH User ' . substr($address, 0, 8),
                'password' => bcrypt(Str::random(32)),
            ]);
        }

        Auth::login($user);

        return response()->json(['success' => true]);
    }

    protected function verifySignature($address, $signature): bool
    {
        $message = config('app.name') . ' wants you to sign in with your Ethereum account.';
        $hash = Keccak::hash(sprintf("\x19Ethereum Signed Message:\n%s%s", strlen($message), $message), 256);

        $sign = [
            'r' => substr($signature, 2, 64),
            's' => substr($signature, 66, 64),
        ];

        $recid = ord(hex2bin(substr($signature, 130, 2))) - 27;

        if ($recid != ($recid & 1)) {
            return false;
        }

        $publicKey = (new \Elliptic\EC('secp256k1'))->recoverPubKey(
            $hash,
            $sign,
            $recid
        );

        $recoveredAddress = '0x' . substr(Keccak::hash(substr(hex2bin($publicKey->encode('hex')), 1), 256), 24);

        return Str::lower($address) === Str::lower($recoveredAddress);
    }
}
