package com.sigansud.app.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.DiffUtil;
import androidx.recyclerview.widget.RecyclerView;

import com.sigansud.app.R;
import com.sigansud.app.models.Repas;
import com.sigansud.app.utils.DateUtils;

import java.util.ArrayList;
import java.util.List;

public class RepasAdapter extends RecyclerView.Adapter<RepasAdapter.RepasViewHolder> {

    private List<Repas> repasList = new ArrayList<>();
    private final Context context;

    public RepasAdapter(Context context) {
        this.context = context;
    }

    public void setRepasList(final List<Repas> newList) {
        if (repasList.isEmpty()) {
            repasList.addAll(newList);
            notifyItemRangeInserted(0, newList.size());
        } else {
            DiffUtil.DiffResult result = DiffUtil.calculateDiff(new DiffUtil.Callback() {
                @Override
                public int getOldListSize() {
                    return repasList.size();
                }

                @Override
                public int getNewListSize() {
                    return newList.size();
                }

                @Override
                public boolean areItemsTheSame(int oldItemPosition, int newItemPosition) {
                    return repasList.get(oldItemPosition).getId() == newList.get(newItemPosition).getId();
                }

                @Override
                public boolean areContentsTheSame(int oldItemPosition, int newItemPosition) {
                    Repas oldRepas = repasList.get(oldItemPosition);
                    Repas newRepas = newList.get(newItemPosition);
                    return oldRepas.isValide() == newRepas.isValide() &&
                            (oldRepas.getObservation() != null ? oldRepas.getObservation().equals(newRepas.getObservation()) : newRepas.getObservation() == null);
                }
            });
            repasList.clear();
            repasList.addAll(newList);
            result.dispatchUpdatesTo(this);
        }
    }

    @NonNull
    @Override
    public RepasViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_repas, parent, false);
        return new RepasViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull RepasViewHolder holder, int position) {
        Repas repas = repasList.get(position);

        holder.tvAnimal.setText(repas.getNomAnimal());
        holder.tvDateTime.setText(DateUtils.formatDateTime(repas.getDate(), repas.getHeure()));
        
        holder.tvQuantite.setText(context.getString(R.string.repas_quantite_format, repas.getQuantiteDonnee()));

        if (repas.getObservation() != null && !repas.getObservation().isEmpty()) {
            holder.tvObservation.setVisibility(View.VISIBLE);
            holder.tvObservation.setText(context.getString(R.string.repas_observation_format, repas.getObservation()));
        } else {
            holder.tvObservation.setVisibility(View.GONE);
        }

        // Statut validé / non validé
        if (repas.isValide()) {
            holder.tvStatut.setText(R.string.repas_valide);
            holder.tvStatut.setTextColor(ContextCompat.getColor(context, R.color.green));
        } else {
            holder.tvStatut.setText(R.string.repas_en_attente);
            holder.tvStatut.setTextColor(ContextCompat.getColor(context, R.color.orange));
        }

        // Soignant
        if (repas.getSoignant() != null) {
            holder.tvSoignant.setText(context.getString(R.string.repas_soignant_format, repas.getSoignant().getNomComplet()));
            holder.tvSoignant.setVisibility(View.VISIBLE);
        } else {
            holder.tvSoignant.setVisibility(View.GONE);
        }
    }

    @Override
    public int getItemCount() { return repasList.size(); }

    public static class RepasViewHolder extends RecyclerView.ViewHolder {
        TextView tvAnimal, tvDateTime, tvQuantite, tvObservation, tvStatut, tvSoignant;

        RepasViewHolder(@NonNull View itemView) {
            super(itemView);
            tvAnimal = itemView.findViewById(R.id.tvAnimalRepas);
            tvDateTime = itemView.findViewById(R.id.tvDateTimeRepas);
            tvQuantite = itemView.findViewById(R.id.tvQuantiteRepas);
            tvObservation = itemView.findViewById(R.id.tvObservationRepas);
            tvStatut = itemView.findViewById(R.id.tvStatutRepas);
            tvSoignant = itemView.findViewById(R.id.tvSoignantRepas);
        }
    }
}
