# **Laravel Broadcast Notifications**


The broadcast channel broadcasts notifications using Laravel's event broadcasting services, allowing your JavaScript powered frontend to catch notifications in realtime. If a notification supports broadcasting, you can define a toBroadcast method on the notification class. This method will receive a $notifiable entity and should return a BroadcastMessage instance. 