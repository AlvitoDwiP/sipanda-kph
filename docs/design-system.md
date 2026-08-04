# SIPANDA UI Design System Documentation

A comprehensive guide to the reusable design system components created for the SIPANDA-KPH enterprise platform. All components utilize design tokens defined via CSS variables and Tailwind extensions, with built-in accessibility (ARIA) and responsive behavior.

---

## Typography Hierarchy

Use the following semantic utility classes to format page texts:
* **Heading 1:** `.text-heading-1` (36px, extra bold, -0.025em tracking)
* **Heading 2:** `.text-heading-2` (30px, bold, -0.025em tracking)
* **Heading 3:** `.text-heading-3` (24px, bold, -0.025em tracking)
* **Heading 4:** `.text-heading-4` (20px, semi-bold, -0.015em tracking)
* **Body:** `.text-body` (14px, regular)
* **Body Small:** `.text-body-sm` (12px, regular)
* **Caption:** `.text-caption` (11px, regular, muted)
* **Label:** `.text-label` (12px, semi-bold)

---

## 1. Button Component (`<x-ui.button>`)

### Purpose
Triggers actions (links, submissions, script functions) with polymorphic properties, transitioning between `button` and `a` tags depending on inputs.

### Props
* `variant` (string): `primary` (default), `secondary`, `outline`, `ghost`, `danger`, `warning`, `success`, `info`.
* `size` (string): `xs`, `sm`, `md` (default), `lg`, `xl`.
* `loading` (boolean): Shows spinner and disables actions.
* `disabled` (boolean): Disables execution.
* `href` (string): If provided, renders an `a` anchor element instead of button.
* `leadingIcon` (string): Lucide icon name to display on the left.
* `trailingIcon` (string): Lucide icon name to display on the right.
* `fullWidth` (boolean): Makes the button spans full width.
* `type` (string): Default is `'button'`.

### Usage Example
```html
<x-ui.button variant="primary" size="md" leadingIcon="plus" href="{{ route('admin.pegawai.create') }}">
    Tambah Pegawai
</x-ui.button>

<x-ui.button type="submit" variant="success" :loading="$loadingState">
    Simpan Perubahan
</x-ui.button>
```

---

## 2. Input Component (`<x-ui.input>`)

### Purpose
A universal input form component that dynamically loads text inputs, textareas, selects, checkboxes, radios, switch toggles, and file uploads.

### Props
* `type` (string): `text` (default), `password`, `email`, `number`, `textarea`, `select`, `checkbox`, `radio`, `switch`, `file`, `date`, `search`.
* `name` (string, required): Element name.
* `label` (string): Field header label.
* `placeholder` (string): Text input placeholder.
* `helpText` (string): Secondary instructions block below the input.
* `hint` (string): Right-aligned metadata flag next to the label.
* `error` (string): Validation error message (forces red border).
* `success` (boolean): Forces green check borders.
* `disabled`/`readonly`/`required` (boolean).
* `options` (array): Key-value pairs for `select` type.
* `prefixIcon` / `suffixIcon` (string): Lucide icon names.
* `characterCount` (boolean): Toggles character indicators.
* `maxlength` (integer): Maximum character boundary.

### Usage Example
```html
<x-ui.input 
    type="text" 
    name="name" 
    label="Nama Lengkap" 
    placeholder="Masukkan nama sesuai KTP" 
    required 
    prefixIcon="user"
/>

<x-ui.input 
    type="password" 
    name="password" 
    label="Sandi Akun" 
/>

<x-ui.input 
    type="switch" 
    name="is_active" 
    label="Aktifkan Status Pegawai" 
    :checked="true"
/>
```

---

## 3. Card Component (`<x-ui.card>`)

### Purpose
Content block wrapper supporting customized styles (dashboard statistics, interactive cards, header dividers, action tags).

### Props
* `variant` (string): `default` (default), `flat`, `bordered`, `dashboard`, `statistics`, `interactive`, `hoverable`.
* `title` (string): Card header title.
* `subtitle` (string): Card sub-heading description.
* `description` (string): Card layout body details.
* `icon` (string): Lucide icon.
* `value` (mixed): Statistic text.
* `trend` (string): Status rate indicator (e.g. `+5%`).
* `trendType` (string): `up`, `down`, `neutral`.

### Usage Example
```html
<x-ui.card variant="statistics" title="Total Pegawai" value="128" icon="users" trend="+4%" trendType="up" />

<x-ui.card title="Profil Pegawai" subtitle="Informasi Utama">
    <x-slot name="actions">
        <x-ui.button variant="outline" size="sm">Edit</x-ui.button>
    </x-slot>
    
    <p class="text-xs text-ui-text-primary">Data Pegawai Perhutani.</p>
</x-ui.card>
```

---

## 4. Table Component (`<x-ui.table>`)

### Purpose
Responsive records visualizer with sticky headers, hover layouts, compact paddings, automated skeleton loading, and empty state integrations.

### Props
* `headers` (array): List of column header names.
* `empty` (boolean): Renders the empty state layout if true.
* `loading` (boolean): Renders pulse loading indicators if true.
* `sticky` (boolean): Fixes header on scroll bounds.
* `striped` (boolean): alternates row bg shades.
* `compact` (boolean): reduces row cell paddings.
* `hover` (boolean): highlights focused rows on hover.

### Usage Example
```html
<x-ui.table :headers="['Nama', 'NIP', 'Jabatan', 'Aksi']" :empty="$pegawai->isEmpty()">
    @foreach($pegawai as $p)
        <tr>
            <td class="px-4 py-3 font-semibold">{{ $p->name }}</td>
            <td class="px-4 py-3">{{ $p->nip }}</td>
            <td class="px-4 py-3">{{ $p->jabatan->name }}</td>
            <td class="px-4 py-3">
                <x-ui.button size="xs" variant="outline">Detail</x-ui.button>
            </td>
        </tr>
    @endforeach
    
    <x-slot name="pagination">
        {{ $pegawai->links() }}
    </x-slot>
</x-ui.table>
```

---

## 5. Badge Component (`<x-ui.badge>`)

### Purpose
Pill visual label indicators for status categorizations.

### Props
* `variant` (string): `primary` (default), `success`, `warning`, `danger`, `info`, `neutral`.
* `styleType` (string): `soft` (default), `solid`, `outline`, `dot`.
* `size` (string): `sm`, `md` (default).

### Usage Example
```html
<x-ui.badge variant="success" styleType="soft">Aktif</x-ui.badge>
<x-ui.badge variant="danger" styleType="dot">Terlambat</x-ui.badge>
```

---

## 6. Alert Component (`<x-ui.alert>`)

### Purpose
Displays warnings, updates, or instructions with self-dismiss buttons.

### Props
* `variant` (string): `info` (default), `success`, `warning`, `danger`.
* `dismissible` (boolean): Toggles closing buttons.
* `icon` (string): Custom Lucide icon replacement.
* `title` (string): Bold header text.
* `description` (string): Body details block.

### Usage Example
```html
<x-ui.alert variant="warning" title="Peringatan Data" dismissible>
    Pastikan NIP pegawai sudah benar sebelum menyimpan data.
</x-ui.alert>
```

---

## 7. Modal Component (`<x-ui.modal>`)

### Purpose
Native HTML `<dialog>` element modal. Locks browser scrolling, traps focus, and closes on Escape or backdrop click natively.

### Props
* `name` (string, required): Uniquely registers modal trigger listeners.
* `show` (boolean): Mounts open if true.
* `title` (string): Modal box header title.
* `maxWidth` (string): `sm`, `md`, `lg`, `xl`, `2xl` to `5xl`.

### Usage Example
```html
<!-- Trigger -->
<x-ui.button @click="$dispatch('open-modal', 'delete-confirm')">
    Hapus
</x-ui.button>

<!-- Modal -->
<x-ui.modal name="delete-confirm" title="Konfirmasi Penghapusan" maxWidth="md">
    <p class="text-xs sm:text-sm text-ui-text-secondary">Apakah Anda yakin ingin menghapus data pegawai ini?</p>
    
    <x-slot name="footer">
        <x-ui.button variant="ghost" @click="$dispatch('close-modal', 'delete-confirm')">Batal</x-ui.button>
        <x-ui.button variant="danger">Ya, Hapus</x-ui.button>
    </x-slot>
</x-ui.modal>
```

---

## 8. Toast Component (`<x-ui.toast>`)

### Purpose
Global notification toast stacking component. Auto displays session flash cards and registers the global `window.showToast` JavaScript API.

### Usage Example
```html
<!-- Mounted in Layout master.blade.php once -->
<x-ui.toast />

<!-- Triggers dynamically in JS -->
<script>
    window.showToast("Data Berhasil Disimpan!", "success");
    window.showToast("Terjadi kesalahan teknis.", "danger");
</script>
```

---

## 9. Empty State (`<x-ui.empty-state>`)

### Purpose
Displays placeholder screens when searches or records query lists yield zero results.

### Props
* `icon` (string): Lucide icon.
* `title` (string).
* `description` (string).

### Usage Example
```html
<x-ui.empty-state 
    icon="users" 
    title="Data Pegawai Kosong" 
    description="Silakan tambahkan data pegawai pertama Anda untuk memulai."
>
    <x-slot name="primaryAction">
        <x-ui.button variant="primary">Tambah Pegawai</x-ui.button>
    </x-slot>
</x-ui.empty-state>
```

---

## 10. Skeleton Loader (`<x-ui.skeleton>`)

### Purpose
Pulses loading block boxes for lists, cards, tables, and layouts.

### Props
* `type` (string): `card` (default), `table`, `dashboard`, `form`, `list`.
* `rows` (integer): Rows indicator count.
* `cols` (integer): Columns indicator count.

### Usage Example
```html
<x-ui.skeleton type="dashboard" />
```

---

## 11. Section Header (`<x-ui.section-header>`)

### Purpose
Visual divider for separating layout grids, forms, and cards.

### Props
* `title` (string, required).
* `description` (string).

### Usage Example
```html
<x-ui.section-header title="Data Kepegawaian" description="Kelola riwayat pangkat dan unit kerja pegawai.">
    <x-slot name="actions">
        <x-ui.button size="sm">Tambah Baru</x-ui.button>
    </x-slot>
</x-ui.section-header>
```

---

## 12. Page Header (`<x-ui.page-header>`)

### Purpose
Top breadcrumb, title, and action buttons wrapper for all views.

### Props
* `title` (string, required).
* `subtitle` (string).

### Usage Example
```html
<x-ui.page-header title="Pegawai Perhutani" subtitle="Kelola seluruh berkas kepegawaian SIPANDA">
    <x-slot name="breadcrumbs">
        <a href="#">Dashboard</a>
        <span>/</span>
        <a href="#" class="text-ui-primary font-bold">Pegawai</a>
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="outline" leadingIcon="download">Unduh Laporan</x-ui.button>
    </x-slot>
</x-ui.page-header>
```
