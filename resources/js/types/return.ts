import type { OrderItem } from './order';

export type OrderReturnReason =
  | 'defective'
  | 'wrong_item'
  | 'damaged_packaging'
  | 'missing_items'
  | 'changed_mind'
  | 'other';

export interface ReturnFormItem extends OrderItem {
  has_return?: boolean;
}

export interface ReturnReasonOption {
  value: OrderReturnReason;
  label: string;
}
