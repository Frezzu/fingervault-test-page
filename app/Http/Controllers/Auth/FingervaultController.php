<?php
/**
 * Created by IntelliJ IDEA.
 * User: bmroz
 * Date: 10/19/2018
 * Time: 16:14
 */

namespace App\Http\Controllers\Auth;


use App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FingervaultController extends Controller
{
    use AuthenticatesUsers;

    private static $FINGERVAULT_USER_TOKEN_COLUMN_NAME = 'fingervault_user_token';
    private static $TOKEN_SIZE = 128;
    private static $TOKEN_DURATION = 'PT5M';
    private static $LOGIN_TOKEN_COLUMN_NAME = 'login_token';

    public function checkLogin(Request $request, $token)
    {
        /** @var App\FingervaultToken $fingervaultToken */
        $fingervaultToken = App\FingervaultToken::where(FingervaultController::$LOGIN_TOKEN_COLUMN_NAME, $token)->first();

        if ($fingervaultToken != null && $fingervaultToken->user != null) {

            if ($fingervaultToken->used_at != null) {
                return redirect('login')->withErrors([
                    'token_already_used' => __('auth.fingervault.token_already_used')
                ]);
            }

            if (Carbon::now()->gt(new Carbon($fingervaultToken->valid_to))) {
                return redirect('login')->withErrors([
                    'token_expired' => __('auth.fingervault.token_expired')
                ]);
            }

            $fingervaultToken->used_at = new \DateTime();
            $fingervaultToken->save();

            Auth::login($fingervaultToken->user);

            return redirect()->intended();
        }

        return redirect('login')->withErrors([
            'token_not_found' => __('auth.fingervault.token_not_found')
        ]);
    }

    public function getLoginToken(Request $request, $userToken)
    {
        /** @var App\User $user */
        $user = App\User::where(FingervaultController::$FINGERVAULT_USER_TOKEN_COLUMN_NAME, $userToken)->first();

        if ($user != null) {
            try {
                $fingervaultToken = new App\FingervaultToken;
                $tokenGenerator = new App\Utils\RandomStringGenerator();

                $fingervaultToken->login_token = $tokenGenerator->generate(FingervaultController::$TOKEN_SIZE);
                $fingervaultToken->valid_to = new \DateTime('now');
                $fingervaultToken->valid_to->add(new \DateInterval(FingervaultController::$TOKEN_DURATION));
                $fingervaultToken->user()->associate($user);

                $isTokenUnique = false;

                while (!$isTokenUnique) {
                    try {
                        $fingervaultToken->save();
                        $isTokenUnique = true;
                    } catch (\Illuminate\Database\QueryException $databaseException) {
                        $errorCode = $databaseException->errorInfo[1];

                        if($errorCode == 1062){
                            $fingervaultToken->login_token = $tokenGenerator->generate(FingervaultController::$TOKEN_SIZE);
                        } else {
                            throw $databaseException;
                        }
                    }
                }


                return response()->json([
                    'token' => $fingervaultToken->login_token,
                    'escapedToken' => rawurlencode($fingervaultToken->login_token),
                    'createdAt' => $fingervaultToken->created_at,
                    'validTo' => $fingervaultToken->valid_to
                ]);
            } catch (\Exception $unknownException) {
                return response()->json([
                    'errors' => 'true',
                    'errorCode' => 500,
                    'errorMessage' => __('auth.fingervault.unknown_error')
                ]);
            }
        }

        return response()->json([
            'errors' => 'true',
            'errorCode' => 404,
            'errorMessage' => __('auth.fingervault.user_not_found')
        ]);
    }
}
