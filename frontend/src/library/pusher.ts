import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
const echo = new Echo({
    broadcaster: 'pusher',
    key: '9e44dc07ce874ae1fd2a',
    cluster: 'ap1',
    forceTLS: false,
});
export default echo;
