<div>
    <h4>Welcome {{ $data['user_name'] }}</h4>
    <p style="margin-top: 20px; color: green;">Please use the following credentials to login into your account</p>
    <div style="display: flex; align-items: center; justify-content: start; gap:10px;">
        <div style="padding: 10px; border-radius: 8px; background: #eee; font-size: 12px;">
            <strong>Email: </strong> {{ $data['email'] }}
            <br/>
            <strong>Password: </strong> {{ $data['password'] }}
        </div>
    </div>
</div>