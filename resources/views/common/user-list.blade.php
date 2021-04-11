<div id="test">To:</div>
<div>
    <select id="email" name="emails[]" multiple="multiple" class="multiple-emails browser-default">
        @foreach($emails as $email)
            <option value="{{$email['email']}}" >{{$email['name']}}
            </option>
        @endforeach
        @foreach($users as $user)
            <option value="{{$user->email}}" >{{$user->email}}
            </option>
        @endforeach
    </select>
</div>
<div>CC:</div>
<div>
    <select id="cc_emails" name="cc_emails[]" multiple="multiple" class="multiple-emails browser-default">
        @foreach($emails as $email)
            <option value="{{$email['email']}}" >{{$email['name']}}
            </option>
        @endforeach
        @foreach($users as $user)
            <option value="{{$user->email}}" >{{$user->email}}
            </option>
        @endforeach
    </select>
</div>
