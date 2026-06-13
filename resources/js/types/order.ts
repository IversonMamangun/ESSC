import { LaravelPaginationItem } from './product';

export type OrderDisplayStatus =
  | 'all'
  | 'to-pay'
  | 'to-ship'
  | 'to-receive'
  | 'completed'
  | 'cancelled'
  | 'returned';

export interface OrderItem {
  product_name: string;
  product_image: string | null;
  variant_name: string | null;
  price: number;
  quantity: number;
}

export interface Order {
  id: number;
  store_name: string;
  status: OrderDisplayStatus;
  shipping_fee: number;
  total: number;
  items: OrderItem[];
}

export interface PaginatedOrders {
  data: Order[];

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
