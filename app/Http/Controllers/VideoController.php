<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use FFMpeg;

use FFMpeg\Format\Video\X264;

// use Pbmedia\LaravelFFMpeg\FFMpeg;

class VideoController extends Controller
{
    public function makeVideo(Request $request)
    {
        $id = Str::random(6);
        $fps=$request->fps;
        // $seconds=$request->time;
        $seconds=20;
        $mantissaDigits=2;
        $upperFont=100;
        $lowerFont=100;
        $color=$request->textColor;
        $name="countdown_timer_$id.mp4";
        $wtr = '-i ~/Pictures/biggles.jpg -filter_complex \
        "[1]scale=iw*0.7:-1[wm];[0][wm]overlay=x=(W-w)/2:y=(H-h)/2" -codec:a copy';
$font = env("APP_FONT", "/").'DIGITALDREAM.ttf';
$featureImage = '-i '.env("APP_BACKGROUND_URL", "/").'/public/images/backgrounds/'.'1.jpg'.' -filter_complex "overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2"';
        $str = "Visit W3Schogols";
$pattern = "/#\w{4,8}/i";
$image=env("APP_BACKGROUND_URL", "/").'/public/images/backgrounds/'.$request->backgroundImage.'.jpg';
if(preg_match($pattern, $request->backgroundImage)){
$bg='-f lavfi -i color=c='.$request->backgroundImage.':s=1280x720:r=24 ';
} else{
    $bg = '-loop 1 -i '.$image ;
}

        $background = "";

        // $command = `ffmpeg -loop 1 -i $image -c:v libx264 -r $fps -t $seconds -pix_fmt yuv420p -vf "fps=$fps,drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf':fontcolor=$color:fontsize=$upperFont:x=(w-text_w)/2:y=(h-text_h)/2:text='%{eif\:($seconds-t)\:d}.%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}',drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf':fontcolor=$color:fontsize=$lowerFont:x=(w-text_w)/2:y=((h-text_h)/2)+$upperFont:text='Elapsed\: %{eif\:(t)\:d}.%{eif\:(mod(t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}'" $name;`;
 $things = env("APP_BACKGROUND_URL", "/").'/public/images/backgrounds/'.'1.jpg';
        $command = `ffmpeg $bg  \
        -c:v libx264 \
        -r $fps \
        -t $seconds \
        -pix_fmt yuv420p \
        -vf "fps=$fps,\
        drawtext=fontfile='$font':\
        fontcolor=$color:\
        fontsize=$upperFont\
        :x=(w-text_w)/2:y=(h-text_h)-40:\
        text='%{eif\:($seconds-t)\:d}\:%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}" \
        $name -loglevel debug -v verbose;`;
  
        
        `ffmpeg -i $name`;
        // $command = `ffmpeg -loop 1 -i $image -filter 'fade=in:5:20:color=yellow' -c:v libx264 -r $fps -t $seconds -pix_fmt yuv420p -vf "fps=$fps,drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf':fontcolor=$color:fontsize=$upperFont:x=(w-text_w)/2:y=(h-text_h)/2:text='%{eif\:($seconds-t)\:d}.%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}" $name -loglevel debug -v verbose;`;
  
$newimg = env("APP_BACKGROUND_URL", "/")."/public/images/backgrounds/1.jpg";

        // $command = "ffmpeg -loop 1 ".
        // "-i $newimg ".
        // "-c:v libx264 ".
        // "-t 15 ".
        // "-pix_fmt yuv420p ".
        // "'-vf drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf': x=(w-text_w)/2: y=(h-text_h)/2:text='hello' ".
        // "-vf scale=1280:720 ".
        // "out21432.mp4;";
        
        // system($command);


        return response()->json(
        [
            'success'=>true,
            'file_name'=> $name,
            'path' => $image,
            'command' => $command,
            'font' => $font
        ],
            200
        );
    }

    public function create(Request $request)
    {
        $relPath = $this->getRelPath();

        $font = $this->getFont($request->counterFont);

        $id = Str::random(6);

        $seconds = $request->time;

        $duration = $this->getTotalDuration($seconds);

        $mantissaDigits = 2;

        $color=$request->textColor;

        $upperFont = 50;

        $fps=$request->fps;

        $name=$this->getFileName($relPath, $id);

        $audio = $this->getAudio($request->audio);
        
        $ft_img_pos = $this->getFeatureImgPos($request->featureImgPos);
        
        $ms=$this->getMs($request->hideMs, $seconds, $mantissaDigits);
        
        $bg = $this->getBackground($request->backgroundImage);

        $hours = $this->getHours($seconds);

        $imgPath= $this->getImagePath($request->featureImage, $relPath, $id);

        $complexFilters = $this->getComplexFilters($ft_img_pos, $font, $color, $upperFont, $hours, $seconds, $ms);

        $command = "ffmpeg \
        $bg \
        -i $imgPath \
        $audio \
        -c:v libx264 \
        -r $fps \
        -t $duration \
        -pix_fmt yuv420p \
        -filter_complex \
         $complexFilters \
         -progress progress/progress-$id.txt \
        $name
        ";


        exec($command." > ".$id."out.txt 2> ".$id."err.txt");



        return response()->json(
        [
            'success'=>true,
            'file_name'=> $name,
            'file_alias' => "countdown_timer_$id.mp4",
             'output' => $output,
             '$returnStatus'=> $returnStatus,
            'command' => $command." > out.txt 2> err.txt",
            // 'type'=> $type,
            // 'imgpath' => $imgPath,
            // '$s'=>$output,
            'font'=> $font,
            'kkk'=>env("APP_FONT", "/"),
        ],
            200
        );
}
    

    private function getTotalDuration($seconds)
    {
        return $seconds+0.5;
    }

    private function getFont($font)
    {
        return env("APP_FONT", "/").$font;
    }

    private function getFileName($path, $id)
    {
        return $path."/countdown_timer_$id.mp4";
    }

    private function getFeatureImgPos($featureImgPos)
    {
        if($featureImgPos === "right"){
            return 'x=W-w:y=(H-h)/2-40';
        }else if($featureImgPos === "left"){
            return 'x=0:y=(H-h)/2-40';
        }else{
            return 'x=(W-w)/2:y=(H-h)/2-40';
        }
    }

    private function getAudio($audioFile)
    {
        if($audioFile){
            return "-stream_loop -1 -i mp3/$audioFile.mp3 -c:v copy ";
        }else{
            return "";
        }
    }

    private function getMs($hideMs, $seconds, $mantissaDigits)
    {
        if($hideMs){
            return "";
        }else{
            return "\:%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}";
        }
    }

    private function getHours($seconds)
    {
        if ($seconds > 3600){
            return '%{eif\:(mod(($seconds-t)/3600, 60))\:d\:2}\:';
        }else{
        
            return ''; 
        }
    }

    private function getComplexFilters($ft_img_pos, $font, $color, $upperFont, $hours, $seconds, $ms)
    {
        return "\"[1]scale=iw*0.2:ih*0.2[wm];[0][wm]overlay=$ft_img_pos:[km];[km]drawtext=fontfile='$font':fontcolor=$color:x=(w-text_w)/2:y=(h-text_h)-40:\
        fontsize=$upperFont:\
        text='$hours%{eif\:(mod(($seconds-t)/60, 60))\:d\:2}\:%{eif\:(mod($seconds-t, 60))\:d\:2}$ms'\"";
    }

    private function getImagePath($featureImage, $relPath, $id)
    {
        $image_info = getimagesize($featureImage);

        $type = (isset($image_info["mime"]) ? explode('/', $image_info["mime"] )[1]: "");


        $imgPath = $relPath.'/'.$id.'.'.$type;
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $featureImage));
        file_put_contents($imgPath, $data);

        return $imgPath;
    }



    private function getBackground($background)
    {
        $pattern = "/#\w{4,8}/i";
        $image=env("APP_BACKGROUND_URL", "/").'/public/images/backgrounds/'.$background.'.jpg';
        if (preg_match($pattern, $background)) {
            $col = $background;
            return "-f lavfi -i color=c=$col:s=1920x1080:r=24 ";
        } else {
            return'-loop 1 -i '.$image ;
        }
    }

    private function getRelPath()
    {
        $path =  "outputs/".date('Y-m-d')."-generated";
        if (!file_exists($path)) {
            mkdir($path, 777, true);
            chmod($path, 0777);
        }
        return $path;
    }
    
    public function createBackup(Request $request)
    {
        //this works fine
        $font = env("APP_FONT", "/").'DIGITALDREAM.ttf';

$str = Str::random(6);
$seconds = 3;
$duration = $seconds+0.05;
$mantissaDigits = 2;
$color='red';
$upperFont = 100;
$fps=25;

$ms="\:%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}";
$ms="";

`ffmpeg -f lavfi \
-i color=c='#e8e8e8':s=1280x720:r=24 \
-i ~/Pictures/biggles.jpg \
-c:v libx264 \
-r 30 \
-t $duration \
-pix_fmt yuv420p \
-filter_complex \
"[1]scale=iw*0.7:-1[wm];[0][wm]overlay=x=(W-w)/2:y=(H-h)/2:[km];[km]drawtext=fontfile='$font'fontcolor=#333333:x=(w-text_w)/2:y=(h-text_h)-40:\
text='%{eif\:(mod(($seconds-t)/3600, 60))\:d\:2}\:%{eif\:(mod(($seconds-t)/60, 60))\:d\:2}\:%{eif\:(mod($seconds-t, 60))\:d\:2}$ms'" \
things$str.mp4
`;
// text='%{eif\:($seconds-t)\:d}\:%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}'" \
// text='%{eif\:(mod(($seconds-t)/3600, 60))\:d\:2}\:%{eif\:(mod(($seconds-t)/60, 60))\:d\:2}\:%{eif\:(mod($seconds-t, 60))\:d\:2}'" \

// drawtext=fontfile='$font':\
// fontcolor=$color:\
// fontsize=$upperFont\
// :x=(w-text_w)/2:y=(h-text_h)-40:\
// text='%{eif\:($seconds-t)\:d}\:%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}" \
return response()->json(
    [
        'success'=>true,
        'file_name'=> $name,
        'path' => $image,
        'command' => $command,
        'font' => $font
    ],
        200
    );
    }

    public function test()
    {
        return "success";
    }

    public function ffmpeg()
    {

        // new FFMpeg\FFMpeg::fromDisk('public')
        // ->open('biggles.jpg')
        // ->export()
        // ->asTimelapseWithFramerate(1)
        // ->inFormat(new X264)
        // ->save('timelapse.mp4');
        // $bgcol = FFMpeg::create();

        // $bgcol->open('images/backgrounds/1.jpg');

        // $ffmpeg = new ffmpeg_wrapper("/usr/local/bin/ffmpeg");

        $ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe',
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ));

        FFMpeg::open('biggles.jpg')
        ->export()
        ->asTimelapseWithFramerate(0.2)
        ->inFormat(new X264)
        ->save('timelapse2.mp4');

        
        // $ffmpeg->save('lll.mp4');
        // ->asTimelapseWithFramerate(1)
        // ->inFormat(new X264)
        // ->save('timelapse.mp4');


        // $ffmpegtwo = new ffmpeg_wrapper("/usr/local/bin/ffmpeg");
        // $ffmpeg = ffmpeg_presets::empty_movie("1280x720",10);
// $ffmpeg->set_output("empty.mp4");
// $ffmpeg->run();

// echo $ffmpeg->response() . PHP_EOL;


        return "success";
    }
}

class ExecAsync {

    public function __construct($cmd) {
        $this->cmd = $cmd;
        $this->cacheFile = ".cache-pipe-".uniqid();
        $this->lineNumber = 0;
    }

    public function getLine() {
        $file = new \SplFileObject($this->cacheFile);
        $file->seek($this->lineNumber);
        if($file->valid())
        {
            $this->lineNumber++;
            $current = $file->current();
            return $current;
        } else
            return NULL;
    }

    public function hasFinished() {
        if(file_exists(".status-".$this->cacheFile) ||
            (!file_exists(".status-".$this->cacheFile) && !file_exists($this->cacheFile)))
        {
            unlink($this->cacheFile);
            unlink(".status-".$this->cacheFile);
            $this->lineNumber = 0;
            return TRUE;
        } else
            return FALSE;
    }

    public function run() {
        if($this->cmd) {
            $out = exec('{ '.$this->cmd." > ".$this->cacheFile." && echo finished > .status-".$this->cacheFile.";} > /dev/null 2>/dev/null &");
        }
    }
}
