<?php

namespace App\Http\Controllers;

use App\Models\Invitation;

class InvitationApiController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function show(Invitation $invitation)
    {
        return $invitation->load('org', 'inviter');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return response('', 204);
    }
}
