// seller
export interface SummaryChartSegment {
  key: string;
  label: string;
  value: number;
}
export interface ProductsSummary {
  total: number;
  totalViews: number;
  chart: SummaryChartSegment[];
}
export interface OrdersSummary {
  total: number;
  chart: SummaryChartSegment[];
}
