import { Component, OnInit } from '@angular/core';
import { CommonModule, NgOptimizedImage, CurrencyPipe } from '@angular/common';
import { FooterComponent } from '../../shared/components/footer/footer.component';
import { RouterModule, Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { faTimes, faSearch, faHeart } from '@fortawesome/free-solid-svg-icons';
import { ProdutoService, Product, PaginatedResponse } from '../../services/product.service';
import { Observable, of, Subject } from 'rxjs';
import { catchError, debounceTime, distinctUntilChanged, switchMap, tap } from 'rxjs/operators';

@Component({
  selector: 'app-pesquisa',
  standalone: true,
  imports: [
    CommonModule,
    CurrencyPipe,
    FooterComponent,
    RouterModule,
    FormsModule,
    FontAwesomeModule,
    NgOptimizedImage
  ],
  templateUrl: './pesquisa.component.html',
  styleUrls: ['./pesquisa.component.scss']
})
export class PesquisaComponent implements OnInit {
  searchTerm: string = '';
  trendingSearches: string[] = ['Bolsa', 'Tênis', 'Camisa'];
  searchResults$: Observable<PaginatedResponse<Product>> | undefined; // Corrigido para PaginatedResponse
  currentPage: number = 1;
  lastPage: number = 1;
  private searchTerms = new Subject<{ term: string; page: number }>();

  faTimesSolid = faTimes;
  faSearchSolid = faSearch;
  faHeartSolid = faHeart;

  isLoading = false;
  error: string | null = null;
  initialLoad = true;

  constructor(private router: Router, private produtoService: ProdutoService) {}

  ngOnInit(): void {
    this.searchResults$ = this.searchTerms.pipe(
      debounceTime(300),
      distinctUntilChanged((prev, curr) => prev.term === curr.term && prev.page === curr.page),
      switchMap(({ term, page }) => {
        this.initialLoad = false;
        if (term.trim() === '') {
          this.isLoading = false;
          this.currentPage = 1;
          this.lastPage = 1;
          return of({ data: [], current_page: 1, last_page: 1, per_page: 0, total: 0, links: [] } as PaginatedResponse<Product>);
        }
        this.isLoading = true;
        this.error = null;
        return this.produtoService.getProdutos(term, page).pipe(
          tap((response: PaginatedResponse<Product>) => {
            this.currentPage = response.current_page;
            this.lastPage = response.last_page;
            this.isLoading = false;
          }),
          catchError(err => {
            console.error('Erro na busca:', err);
            this.error = 'Erro ao buscar produtos.';
            this.isLoading = false;
            this.currentPage = 1;
            this.lastPage = 1;
            return of({ data: [], current_page: 1, last_page: 1, per_page: 0, total: 0, links: [] } as PaginatedResponse<Product>);
          })
        );
      })
    );

    // Dispara uma busca inicial vazia
    this.searchTerms.next({ term: '', page: 1 });
  }

  search(term: string): void {
    this.currentPage = 1; // Reseta para a primeira página ao mudar o termo
    this.searchTerms.next({ term, page: this.currentPage });
  }

  clearSearch(): void {
    this.searchTerm = '';
    this.search(this.searchTerm);
  }

  clickTrendingSearch(term: string): void {
    this.searchTerm = term;
    this.search(this.searchTerm);
  }

  closeSearchPage(): void {
    window.history.back();
  }

  toggleFavorite(productId: number, event: Event): void {
    event.stopPropagation();
    console.log('Favoritar/Desfavoritar produto com ID:', productId);
    // Implementar lógica de favorito
  }

  goToProductDetail(productId: number): void {
    this.router.navigate(['/telaproduto', productId]);
  }

  changePage(page: number): void {
    this.currentPage = page;
    this.searchTerms.next({ term: this.searchTerm, page: this.currentPage });
  }

  getImagemUrl(path: string): string {
    return `${path}`; // Ajuste para environment.apiUrl se necessário
  }
}
