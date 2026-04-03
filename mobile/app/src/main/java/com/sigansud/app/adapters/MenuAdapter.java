package com.sigansud.app.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.sigansud.app.R;
import com.sigansud.app.models.Menu;

import java.util.ArrayList;
import java.util.List;

public class MenuAdapter extends RecyclerView.Adapter<MenuAdapter.MenuViewHolder> {

    public interface OnMenuActionListener {
        void onValiderMenu(Menu menu);
    }

    private List<Menu> menus = new ArrayList<>();
    private final Context context;
    private final OnMenuActionListener listener;

    public MenuAdapter(Context context, OnMenuActionListener listener) {
        this.context = context;
        this.listener = listener;
    }

    public void setMenus(List<Menu> menus) {
        this.menus = menus;
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public MenuViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_menu, parent, false);
        return new MenuViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull MenuViewHolder holder, int position) {
        Menu menu = menus.get(position);

        holder.tvEspece.setText("Menu #" + menu.getId());
        holder.tvDate.setText("");

        // Afficher l'aliment du menu
        if (menu.getAliment() != null && !menu.getAliment().isEmpty()) {
            holder.tvAliments.setText("• " + menu.getAliment());
        } else {
            holder.tvAliments.setText("Aucun aliment renseigné");
        }

        holder.tvQuantite.setText("Quantité : " + menu.getQuantite() + " g");

        holder.btnValider.setOnClickListener(v -> {
            if (listener != null) listener.onValiderMenu(menu);
        });
    }

    @Override
    public int getItemCount() { return menus.size(); }

    static class MenuViewHolder extends RecyclerView.ViewHolder {
        TextView tvEspece, tvDate, tvAliments, tvQuantite;
        Button btnValider;

        MenuViewHolder(@NonNull View itemView) {
            super(itemView);
            tvEspece = itemView.findViewById(R.id.tvEspeceMenu);
            tvDate = itemView.findViewById(R.id.tvDateMenu);
            tvAliments = itemView.findViewById(R.id.tvAliments);
            tvQuantite = itemView.findViewById(R.id.tvQuantiteTotale);
            btnValider = itemView.findViewById(R.id.btnValiderMenu);
        }
    }
}