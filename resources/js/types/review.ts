import { OrderItem } from './order';

interface Review {
  id: number;
  rating: number;
  comment: string | null;
  video: string | null;
  is_anonymous: boolean;
  created_at: string | null;
  images: string[];
}

export interface ReviewShow extends OrderItem {
  review: Review | null;
}
