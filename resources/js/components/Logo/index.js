import React from 'react';

const Logo = () => {
    return (
        <div className="logo">
            <h1>Events:<br/>
                Countdown
            </h1>
            {/* <h3>{`${props.region}'s most popular webinar tool`}</h3> */}
            <h2>Create an online countdown timer, perfect for enhancing your online or in-person event flow. Personalise it to match your event branding, overlay an audio track and download - it’s that simple!</h2>
            <ul>
                <li><h3>It’s easy</h3><p>Just follow each step until you have a timer that looks and sounds great.</p></li>
                <li><h3>Increase your viewership</h3><p>Playing a countdown timer before your event starts is a great way to draw in crowds for live social media streaming.</p></li>
                <li><h3>First impressions are everything</h3><p>The first thing event attendees will see is a crisp and professional looking timer, setting the tone for the rest of the event. It will also provide panellists with a clear indicator of when to jump in and start the event.</p></li>
                <li><h3>Promote</h3><p>Using a timer is a great way to display sponsor logos, speaker info, or even promote a ‘Call to Action’.</p></li>

            </ul>
        </div>
    );
};

export default Logo;


