<?xml version='1.0' encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" encoding="UTF-8" indent="yes" />
  <xsl:strip-space elements="*" />

  <xsl:template match="/">
    <div class="meteo--list">
      <xsl:apply-templates select="previsions/echeance" />
    </div>
  </xsl:template>

  <!-- No useless data -->
  <xsl:template match="echeance"></xsl:template>
  <!-- Render HTML element -->
  <xsl:template match="echeance" mode="render">
    <div class="meteo--item">
      <div class="meteo--item_time">
        <xsl:value-of select="substring(@timestamp, 11, 6)" />
      </div>
      <!-- Température -->
      <xsl:variable name="temperature">
          <xsl:value-of select="round(temperature/level[@val='2m'] - 273.15)"/>
      </xsl:variable>
      <div class="meteo--item_temperature">
        <xsl:value-of select="$temperature" />
      </div>
      <!-- Status -->
      <div class="meteo--item_status">
        <xsl:choose>
          <xsl:when test="pluie > 0">
            <i class="fas fa-cloud-rain"></i>
          </xsl:when>
          <xsl:otherwise>
            <i class="fas fa-sun"></i>
          </xsl:otherwise>
        </xsl:choose>
      </div>
      <!-- Humidité -->
      <div class="meteo--item_humid">
        <xsl:value-of select="humidite/level" />
        %
      </div>
      <!-- Neige -->
      <xsl:choose>
        <xsl:when test="risque_neige = 'oui'">
          <div class="meteo--item_neige">
            <i class="fas fa-snowflake"></i>
          </div>
        </xsl:when>
      </xsl:choose>
      <!-- Conseils -->
      <div class="meteo--item_neige">
        <xsl:choose>
          <xsl:when test="pluie > 0">
            <xsl:choose>
              <xsl:when test="$temperature &lt; 10">
                Uniquement si t'a pas d'autres solutions !
              </xsl:when>
              <xsl:otherwise>
                Sort la veste et prend un parapluie dans le sac.
              </xsl:otherwise>
            </xsl:choose>
          </xsl:when>
          <xsl:otherwise>
              <xsl:choose>
                <xsl:when test="$temperature &lt; 10">
                  Prend un bonnet... On sait jamais...
                </xsl:when>
                <xsl:otherwise>
                  Les conditions sont idéales
                </xsl:otherwise>
              </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
      </div>
    </div>
  </xsl:template>
  <!-- Various echances -->
  <xsl:template match="echeance[@hour=3]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
  <xsl:template match="echeance[@hour=6]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
  <xsl:template match="echeance[@hour=9]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
  <xsl:template match="echeance[@hour=15]">
    <xsl:apply-templates select="." mode="render" />
  </xsl:template>
</xsl:stylesheet>