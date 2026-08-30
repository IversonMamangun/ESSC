import { LaravelPaginationItem } from './product';
import { User } from './auth';

export interface MessageAttachmentType {
  id: number;
  url: string;
  original_name: string;
  mime_type: string;
  size: number;
}

export interface ConversationMessage {
  id: number;
  body: string | null;
  created_at: string;
  read_at: string | null;
  sender: User;
  attachments: MessageAttachmentType[];
}

export interface SellerConversationShow {
  id: number;
  user: User;
  messages: ConversationMessage[];
}

export interface SellerConversationIndex {
  id: number;
  store_unread_count: number;
  last_message_at: string | null;
  last_message: string | null;
  user: User;
}

export interface PaginatedSellerConversationIndex {
  data: SellerConversationIndex[];

  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };

  meta: {
    current_page: number;
    from: number;
    last_page: number;
    links: LaravelPaginationItem[];
    path: string;
    per_page: number;
    to: number;
    total: number;
  };
}
