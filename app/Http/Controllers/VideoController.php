<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use FFMpeg;

use FFMpeg\Format\Video\X264;

class VideoController extends Controller
{
    public function makeVideo(Request $request)
    {
        $id = Str::random(6);
        $fps=8;
        $seconds=$request->time;
        $mantissaDigits=2;
        $upperFont=100;
        $lowerFont=100;
        $color=$request->textColor;
        $name="countdown_timer_$id.mp4";
        // $image=asset('/images/backgrounds/').'/'.$request->backgroundImage.'.jpg';
        $image=env("APP_BACKGROUND_URL", "/").'/public/images/backgrounds/'.$request->backgroundImage.'.jpg';
        // env("APP_BACKGROUND_URL", "/").
// $image='~/Desktop/events_timer_v2/Black-Background.png';
        // $image='.jpg';
        // $command = `ffmpeg -loop 1 -i $image -c:v libx264 -r $fps -t $seconds -pix_fmt yuv420p -vf "fps=$fps,drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf':fontcolor=$color:fontsize=$upperFont:x=(w-text_w)/2:y=(h-text_h)/2:text='%{eif\:($seconds-t)\:d}.%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}',drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf':fontcolor=$color:fontsize=$lowerFont:x=(w-text_w)/2:y=((h-text_h)/2)+$upperFont:text='Elapsed\: %{eif\:(t)\:d}.%{eif\:(mod(t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}'" $name;`;
 
        $command = `ffmpeg -loop 1 -i $image -filter 'fade=in:5:20:color=yellow' -c:v libx264 -r $fps -t $seconds -pix_fmt yuv420p -vf "fps=$fps,drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf':fontcolor=$color:fontsize=$upperFont:x=(w-text_w)/2:y=(h-text_h)/2:text='%{eif\:($seconds-t)\:d}.%{eif\:(mod($seconds-t, 1)*pow(10,$mantissaDigits))\:d\:$mantissaDigits}" $name -loglevel debug -v verbose;`;
        
$newimg = env("APP_BACKGROUND_URL", "/")."/public/images/backgrounds/1.jpg";

        $command = "ffmpeg -loop 1 ".
        "-i $newimg ".
        "-c:v libx264 ".
        "-t 15 ".
        "-pix_fmt yuv420p ".
        "'-vf drawtext=fontfile='/usr/share/fonts/urw-base35/C059-Bold.otf': x=(w-text_w)/2: y=(h-text_h)/2:text='hello' ".
        "-vf scale=1280:720 ".
        "out21432.mp4;";
        
        system($command);


        return response()->json(
        [
            'success'=>true,
            'file_name'=> $name,
            'path' => $image,
            'ccc' => env("APP_BACKGROUND_URL", "/"),
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

        FFMpeg::fromDisk('public')
        ->open('images/backgrounds/1.jpg')
        ->export()
        ->asTimelapseWithFramerate(1)
        ->inFormat(new X264)
        ->save('timelapse.mp4');

        return "success";
    }
}

