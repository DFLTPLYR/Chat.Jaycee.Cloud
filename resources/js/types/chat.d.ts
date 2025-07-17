export interface WebsocketUsers {
    id: number;
    user?: string;
    profile_picture?: profile_picture;
    isTyping?: boolean;
}

export interface ChatPayload {
    user: ChatUser;
    message: ChatMessage;
}

export interface ChatMessage {
    message: string;
    sender_id: number;
    updated_at: Date;
    created_at: Date;
    id: number;
}

export interface ChatUser {
    id: number;
    name: string;
    email: string;
    email_verified_at: Date;
    created_at: Date;
    updated_at: Date;
    profile_photo_url: string;
}
