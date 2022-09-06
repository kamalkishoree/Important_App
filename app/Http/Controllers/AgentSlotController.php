<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Exception;
use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Traits\ApiResponser;

use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver;
use App\Model\{Agent,AgentSlot,AgentSlotDate,SlotDay};


class AgentSlotController extends Controller
{
    use ApiResponser;
   

}
