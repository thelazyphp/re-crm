<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parsing\Parser;

class ParserController extends Controller
{
    /**
     * @var \App\Parsing\Parser
     */
    protected $parser;

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'string',
            'token' => 'required|string',
        ]);

        if ($request->token != env('PARSER_TOKEN')) {
            $data = [
                'status' => false,
                'message' => 'Parser token in not valid!',
            ];

            return response()->json($data, 403);
        }

        $elapsed = $this->parser->startParsing(
            $request->has('group_id') ? explode(',', $request->groups) : null
        );

        $data = [
            'status' => true,
            'message' => 'Parser finished with elapsed time: '.$elapsed.'s.',
        ];

        return response()->json($data, 200);
    }
}
