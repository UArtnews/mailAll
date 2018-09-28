<?php

class EmailController extends \BaseController
{
    private $excel;

    public function __construct(){
        //Make ExcelGet stuff available for this Controller
        $this->excel = App::make('ExcelGet');
        $this->switchMail = App::make('SwitchMail');
    }

    public function sendEmail($instanceName, $publication_id){

        $instance = Instance::where('name', $instanceName)->first();

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isEditable'               => false,
            'shareIcons'               => false,
        );

        //Validate Input
        $validator = Validator::make(
            array(
                'addressTo'     => Input::get('addressTo'),
                'addressFrom'   => Input::get('addressFrom'),
                'nameFrom'   => Input::get('nameFrom'),
                'replyTo'       => Input::get('replyTo'),
                'subject'       => Input::get('subject')
            ),
            array(
                'addressTo'     => 'required|email',
                'addressFrom'   => 'required|email',
                'nameFrom'      => 'required',
                'replyTo'       => 'required|email',
                'subject'       => 'required'
            )
        );

        if($validator->fails()){
            $errorMessage = 'Please correct the following:</br>';
            foreach($validator->messages()->all() as $field => $message){
                $errorMessage .= ucfirst($message)."</br>";
            }
            return Redirect::back()->withError($errorMessage);
        }


        //Get This Publication
        $publication = Publication::where('id', $publication_id)->where('instance_id', $instance->id)->withArticles()->first();

        //Publish if this is a real deal publish things
        if(!Input::has('isTest')){
            foreach($publication->articles as $article){
                $thisArticle = Article::find($article->id);
                $thisArticle->published = 'Y';
                $thisArticle->save();
            }
            $publication->published = 'Y';
            $publication->save();
        }

        $data['publication'] = $publication;
        $data['isEmail'] = true;

        $html = View::make('emailPublication', $data)->render();
        $css = View::make('emailStyle', $data)->render();

        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
        $inliner->setHTML($html);
        $inliner->setCSS($css);

        $inlineHTML = $inliner->convert();

        $sendingAddress = isset($data['tweakables']['publication-email-address']) ? $data['tweakables']['publication-email-address'] : $data['default_tweakables']['publication-email-address'];
        $this->switchMail->gmail($sendingAddress);
        Mail::send('html', array('html' => $inlineHTML), function($message){
            $message->to(Input::get('addressTo'))
                ->subject(Input::has('subject') ? stripslashes(Input::get('subject')) : '')
                ->from(Input::get('addressFrom'), Input::has('nameFrom') ? Input::get('nameFrom') : '')
                ->replyTo(Input::get('replyTo'), Input::has('nameFrom') ? Input::get('nameFrom') : '');
        });

        return Redirect::back()->withSuccess("Publication successfully sent!");
    }

    public function mergeEmail($instanceName, $publication_id){
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time','2400');
        $instance = Instance::where('name', $instanceName)->first();

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isEditable'               => false,
            'shareIcons'               => false,
        );

        //Validate Input
        $validator = Validator::make(
            array(
                'addressField'     => Input::get('addressField'),
                'addressFrom'   => Input::get('addressFrom'),
                'nameFrom'   => Input::get('nameFrom'),
                'replyTo'       => Input::get('replyTo'),
                'subject'       => Input::get('subject')
            ),
            array(
                'addressField'     => 'required',
                'addressFrom'   => 'required|email',
                'nameFrom'      => 'required',
                'replyTo'       => 'required|email',
                'subject'       => 'required'
            )
        );

        if($validator->fails()){
            $errorMessage = 'Please correct the following:</br>';
            foreach($validator->messages()->all() as $field => $message){
                $errorMessage .= ucfirst($message)."</br>";
            }
            return Redirect::back()->withError($errorMessage);
        }

        //Get This Publication
        $publication = Publication::where('id', $publication_id)->
        where('instance_id', $instance->id)->withArticles()->first();

        $data['publication'] = $publication;
        $data['isEmail'] = true;

        $mergeFileName = '';
        $mergePath = "/web_content/share/mailAllSource/docs/" . $instance->name;

        //Do File Upload, store as latestMerge.xlsx/xls
        if(Input::hasFile('mergeFile')){
            if(Input::file('mergeFile')->isValid() && ( Input::file('mergeFile')->getClientOriginalExtension() == 'xls' || Input::file('mergeFile')->getClientOriginalExtension() == 'xlsx' || Input::file('mergeFile')->getClientOriginalExtension() == 'csv' ) ){
                if(!file_exists($mergePath)){
                    mkdir($mergePath);
                }
                $mergeFileName = "latestMerge.".Input::file('mergeFile')->getClientOriginalExtension();
                if(file_exists($mergePath . "/" . $mergeFileName)){
                    unlink($mergePath . "/" . $mergeFileName);
                }
                Input::file('mergeFile', 0775)->move($mergePath, $mergeFileName);
            }else{
                return Redirect::back()->withError('Invalid Merge File Uploaded. XLS or XLSX files only!');
            }
        }else{
            return Redirect::back()->withError('No Merge File Uploaded!');
        }

        //Publish if this is a real deal publish things
        if(!Input::has('isTest')){
            foreach($publication->articles as $article){
                $thisArticle = Article::find($article->id);
                $thisArticle->published = 'Y';
                $thisArticle->save();
            }

            $publication->published = 'Y';
            $publication->save();
        }

        $html = View::make('emailPublication', $data)->render();
        $css = View::make('emailStyle', $data)->render();

        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
        $inliner->setHTML($html);
        $inliner->setCSS($css);

        $inlineHTML = $inliner->convert();
        $mergedHTML = '';
        $sentCount = 0;
        $failCount = 0;
        $failDetails = array();

        //Scan for replacements
        $replaceColumns = array();
        $replacePattern = '/\*\*.*?\*\*/';
        preg_match_all($replacePattern, $inlineHTML, $replaceColumns, PREG_PATTERN_ORDER);
        $replaceColumns = $replaceColumns[0];
        array_push($replaceColumns, strtolower(Input::get('addressField')));
        //Strip the asterisks
        foreach($replaceColumns as &$matcher){
            $matcher = str_replace('*','',$matcher);
            $matcher = strtolower($matcher);
        }

        //Mail Switching
        $sendingAddress = isset($data['tweakables']['publication-email-address']) ? $data['tweakables']['publication-email-address'] : $data['default_tweakables']['publication-email-address'];
        $this->switchMail->gmail($sendingAddress);

        if(Input::has('isTest')){
            //Do a single merge
            $mergedHTML = $inlineHTML;
            foreach($this->excel->oneRow($mergePath . "/" . $mergeFileName) as $index => $value){
                $pattern = '**' . $index . '**';
                $mergedHTML = str_replace($pattern, $value, $mergedHTML);
            }
            if(Input::has('testTo') && Input::has('addressFrom')){
                Mail::send('html', array('html' => $mergedHTML), function($message){
                        $message->to(Input::get('testTo'))
                            ->subject(Input::has('subject') ? stripslashes(Input::get('subject')) : '')
                            ->from(Input::get('addressFrom'), Input::has('nameFrom') ? Input::get('nameFrom') : 'noreply@uakron.edu')
                            ->replyTo(Input::get('replyTo'), Input::has('nameFrom') ? Input::get('nameFrom') : '');
                    });
                $data['success'] = true;
            }else{
                $data['error'] = true;
            }
        }else{
            //Do the big-daddy merge
            $addresses = $this->excel->asArray($mergePath . "/" . $mergeFileName, $replaceColumns);

            //For each recipient/row in the merge file
            foreach($addresses as $address) {
                $addressField = strtolower(Input::get('addressField'));
                $addressTo = $address[$addressField];
                $mergedHTML = $inlineHTML;
                //For each column in this row try to replace the contents of the file
                foreach ($address as $index => $value) {
                    $pattern = '**' . $index . '**';
                    $mergedHTML = str_replace($pattern, $value, $mergedHTML);
                }

                if (Input::has('addressFrom') && Input::has('replyTo')) {
                    $addressTo = trim($addressTo);
                    $validator = Validator::make(array('email' => $addressTo), array('email' => 'email|required'));
                    if($validator->fails()){
                        if(count($address) > 0) {
                            $failDetails[$failCount] = $address;
                            $failCount ++;
                            $data['error'] = true;
                        }
                    }else{
                        $attempts = 0;
                        $attemptCeiling = 30;
                        do {
                            $success = true;
                            try {
                                Mail::send(
                                    'html',
                                    array('html' => $mergedHTML),
                                    function ($message) use ($addressTo) {
                                        $message->to($addressTo)
                                            ->subject(Input::has('subject') ? stripslashes(Input::get('subject')) : '')
                                            ->from(
                                                Input::get('addressFrom'),
                                                Input::has('nameFrom') ? Input::get('nameFrom') : ''
                                            )
                                            ->replyTo(
                                                Input::get('replyTo'),
                                                Input::has('nameFrom') ? Input::get('nameFrom') : ''
                                            );
                                    }
                                );
                            }catch(Exception $e){
                                $attempts++;
                                $success = false;
				sleep(1);
                            }
                        }while(!$success && $attempts < $attemptCeiling);

                        $sentCount++;
                        $data['success'] = true;
                    }
                } else {
                    if(count($address) > 0) {
                        $failDetails[$failCount] = $address;
                        $failCount++;
                        $data['error'] = true;
                    }
                }
            }
        }

        //Remove the merge file
        if(file_exists($mergePath . "/" . $mergeFileName))
            unlink($mergePath . "/" . $mergeFileName);

        //Write out fail log if necessary
        if($failCount > 0){
            $logPath = '/web_content/share/mailAllSource/public/logs/'.$instance->name;
            if(!file_exists($logPath)){
                mkdir($logPath);
            }

            $failLog = '';
            foreach($failDetails as $index => $failDetail){
                $failLog .= "Error Record #" . ($index+1);
                $failLog .= "\n\tMerge Details:";
                foreach($failDetail as $index => $value){
                    $failLog .= "\n\t\t$index: $value";
                }
                $failLog .="\n\n";
            }

            file_put_contents($logPath . "/mergeErrors".date('Y-m-d').".txt", $failLog);
        }

        //Display the results of the last email, might as well, it'll be merged
        $data['isEmail'] = true;
        if($sentCount > 0 && $failCount > 0){
            return Redirect::back()
                ->withSuccess("$sentCount message(s) successfully sent!")
                ->withError("$failCount message(s) encountered an error! <a href=\"" . URL::to('logs/'.$instance->name.'/mergeErrors'.date('Y-m-d').'.txt') . "\" class=\"btn btn-xs btn-danger\">View Error Log</a>");
        }elseif($sentCount > 0){
            return Redirect::back()->withSuccess("$sentCount message(s) successfully sent!");
        }elseif($failCount > 0){
            return Redirect::back()
                ->withError("$failCount message(s) encountered an error! <a href=\"" . URL::to('logs/'.$instance->name.'/mergeErrors.txt') . "\" class=\"btn btn-xs btn-danger\">View Error Log</a>");
        }else{
            return Redirect::back()->withSuccess("Message(s) successfully sent!");
        }
    }

}
