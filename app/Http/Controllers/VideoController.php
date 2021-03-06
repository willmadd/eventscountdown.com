<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use FFMpeg;

use FFMpeg\Format\Video\X264;

use App\Jobs\CreateCountdownVideo;

use Carbon\Carbon;

use Illuminate\Support\Facades\File;

use Image;

use Auth;

use App\Models\RecentVideos;

use Illuminate\Support\Facades\DB;

// use Pbmedia\LaravelFFMpeg\FFMpeg;

class VideoController extends Controller
{

    public function cleanup(Request $request)
    {
        $id = $request->vId;

        File::delete(public_path("progress/progress-$id"));

        return response()->json(
            [
                'success'=>true,
            ],
                200
            );
    }

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
        $res = $this->getQuality($request->q);

        $relPath = $this->getRelPath();

        $font = $this->getFont($request->counterFont);

        $id = Str::random(6);

        $seconds = $request->time;

        $duration = $this->getTotalDuration($seconds);

        $mantissaDigits = 2;

        $color=$request->textColor;

        $upperFont = 100;

        $fps=$request->fps;

        $name=$this->getFileName($relPath, $id);

        $audio = $this->getAudio($request->audio);
        
        $ft_img_pos = $this->getFeatureImgPos($request->featureImgPos);
        
        $ms=$this->getMs($request->hideMs, $seconds, $mantissaDigits);
        
        $bg = $this->getBackground($request->backgroundImage, $res);

        $hours = $this->getHours($seconds);

        $imgPath= $this->getImagePath($request->featureImage, $relPath, $id, $res);

        //input at 3/4 of the output taking into account 5% off set from top
        $featureImgHeight = ($res*0.80);

        $complexFilters = $this->getComplexFilters($ft_img_pos, $font, $color, $upperFont, $hours, $seconds, $ms, $featureImgHeight);

        $publicPath = public_path();


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
         -progress $publicPath/progress/progress-$id \
        $name
        ";
        
      
        $databaseR="";

        if(auth()->user()) {

        $thumb = $this->makeThumb($request->featureImage,200);

        $databaseR = $this->saveVideoToUserProfile(auth()->user()->id, $res, $thumb, $imgPath, $seconds, $id, $request->backgroundImage, $request->audio, $request->counterFont, $request->hideMs, $request->textColor);
        }

            $job = (new CreateCountdownVideo($command, $id))->delay(Carbon::now()->addSeconds(2));

            dispatch($job);

            // exec($command);


        return response()->json(
        [
            'success'=>true,
            'file_name'=> $name,
            'id'=> $id,
            'file_alias' => "countdown_timer_$id.mp4",
            'destination' => $this->getDetination($id),
        ],
            200
        );
}
    
private function saveVideoToUserProfile($userId, $res, $thumb, $imgPath, $seconds, $vId, $background, $audio, $font, $ms , $textColor)
{
    RecentVideos::create([
        'user_id'=>$userId,
        'thumbnail'=>$thumb,
        'imgurl'=>$imgPath,
        'duration'=>$seconds,
        'background'=>$background,
        'audio'=>$audio,
        'font'=>$font,
        'hide_ms'=>$ms,
        'font_color'=>$textColor,
        'font_size'=>100,
        'res'=>$res,
        'vId'=>$vId
    ]);

    $ids = RecentVideos::select('id')->orderBy('created_at', 'DESC')->skip(2)->take(10)->get();
    DB::table('recent_videos')->whereIn('id', $ids)->delete();
    return $ids;
}

private function makeThumb($img, $resolution)
{
    $width=$resolution;

    $height=$resolution;

    $thumb = Image::make($img);

    $thumb->height() > $thumb->width() ? $width=null : $height=null;
    $thumb->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });
    return (string) $thumb->encode('data-url');
}

    private function getTotalDuration($seconds)
    {
        return $seconds+0.15;
    }

    private function getQuality($q)
    {
        if($q===3){
            return 1080;
        }else if($q===2){
            return 720;
        } else{
            return 480;
        }
        return $seconds+0.15;
    }

    private function getFont($font)
    {
        return public_path().'/fonts/'.$font;
    }

    private function getFileName($path, $id)
    {
        return $path."/countdown_timer_$id.mp4";
    }

    private function getDetination($id)
    {
        return "/outputs/".date('Y-m-d')."-generated/countdown_timer_$id.mp4";
    }

    private function getFeatureImgPos($featureImgPos)
    {
        if($featureImgPos === "right"){
            return 'x=W-w:y=40';
        }else if($featureImgPos === "left"){
            return 'x=0:y=40';
        }else{
            return 'x=(W-w)/2:y=40';
        }
    }

    private function getAudio($audioFile)
    {
        $publicPath = public_path();
        if($audioFile){
            return "-stream_loop -1 -i $publicPath/mp3/$audioFile.mp3 -c:v copy ";
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

    private function getComplexFilters($ft_img_pos, $font, $color, $upperFont, $hours, $seconds, $ms, $featureImgHeight)
    {
        $yheight=strval($featureImgHeight);

        return "\"[1]scale=w=-1:h=".$featureImgHeight."[wm];[0][wm]overlay=$ft_img_pos:[km];\
        [km]drawtext=fontfile='$font':fontcolor=$color:x=(w-text_w)/2:y=(h-text_h)-40:\
        fontsize=$upperFont:\
        text='$hours%{eif\:(mod(($seconds-t)/60, 60))\:d\:2}\:%{eif\:(mod($seconds-t, 60))\:d\:2}$ms',drawtext=fontcolor=#ffffff:x=10/2:y=(h-text_h)-10:\
        fontsize=20:\
        text='Made for free with eventscountdown.com'\"";
    }

    private function getImagePath($featureImage, $relPath, $id, $res)
    {
        $image_info = getimagesize($featureImage);

        $type = (isset($image_info["mime"]) ? explode('/', $image_info["mime"] )[1]: "");


        $imgPath = $relPath.'/'.$id.'.'.$type;

        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $featureImage));


        $width = ceil(($res*16/9))*0.80;
        if($width % 2 == 1) $width++;

    
 
        $height = $res * 0.80;

        $imgf = Image::make($data);

        $imgf->height() > $imgf->width() ? $width=null : $height=null;
        $imgf->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imgf->save($imgPath,50);
        // ..

        // file_put_contents($imgPath, $imgf);

        return $imgPath;
    }



    private function getBackground($background, $res)
    {
        $width = ceil(($res*16/9));
        if($width % 2 == 1) $width++;

        $pattern = "/#\w{4,8}/i";
        $image=public_path().'/images/backgrounds/'.$background.'_'.$res.'.jpg';
        if (preg_match($pattern, $background)) {
            $col = $background;
            return "-f lavfi -i color=c=$col:s=".$width."x".$res.":r=24 ";
        } else {
            return'-loop 1 -i '.$image ;
        }
    }

    private function getRelPath()
    {
        $path =  public_path()."/outputs/".date('Y-m-d')."-generated";
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


        return "success";
    }
}
